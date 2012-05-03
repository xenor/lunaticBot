<?php
class /*MODULE_ID*/
{
	public $core;
	public $last;
	public function __construct(&$core)
	{
		$this->core = &$core;
		$this->module_id = "/*MODULE_ID*/";
		$this->core->modules->registerEvent("PRIVMSG",$this);
	}
	private function GetTv($channel, $now){
		$channel = strtolower($channel);
		$stations = array(
			"RTL" => array("assitv"),
			"RTL II" => array("assitv2", "rtl2", "rtl 2"),
			"Sat.1" => array("sat1", "sat", "sateins", "sat eins"),
			"ProSieben" => array("pro7", "pro 7", "pro sieben", "prosieben"),
			"Das Erste" => array("ard", "erste", "1", "daserste"),
			"ZDF" => array("das zweite", "2", "daszweite", "zweite")
		);
		for ($i = 0; $i < count($stations); $i++){
			$key = array_keys($stations);
			$key = $key[$i];
			foreach($stations[$key] as $compare){
				if (strtolower($channel) == $compare){
					$channel = $key;
					break(2);
				}
			}
		}
		$tv = array();
		switch ($now){
			//case "20:15":
			case "20.15":
				$feed = new SimpleXMLElement("http://www.tvspielfilm.de/tv-programm/rss/heute2015.xml", 0, true);
				break;
			//case "22:00":
			case "22.00":
				$feed = new SimpleXMLElement("http://www.tvspielfilm.de/tv-programm/rss/heute2200.xml", 0, true);
				break;
			case "":
				$feed = new SimpleXMLElement("http://www.tvspielfilm.de/tv-programm/rss/jetzt.xml", 0, true);
				break;
			default:
				return false;
		}
		foreach($feed->channel->item as $item){
			$explode = explode(" | ", $item->title);
			if (strtolower($explode[1]) == strtolower($channel)){
				return (object) array(
					"time" => $explode[0],
					"title" => $explode[2],
					"channel" => $explode[1]
				);
			}
		}
		return false;
	}
	public function PRIVMSG($event_data)
	{
		if($event_data->message == "omg")
		{
			$this->core->privmsg($event_data->target,$event_data->nick.", WAS IST LOS?! :O");
		}
		elseif(substr($event_data->message,0,strlen($this->core->config->nick)) == $this->core->config->nick)
		{
			$msg = substr($event_data->message,strlen($this->core->config->nick) + 2);
			if(
				$msg == "ich hab dich lieb" ||
				$msg == "ich hab dich lieb <3" ||
				$msg == "ich hab dich lieb :)" ||
				$msg == "ich liebe dich" ||
				$msg == "ich liebe dich <3" ||
				$msg == "ich liebe dich :)" ||
				$msg == "ich mag dich sehr" ||
				$msg == "ich mag dich sehr <3" ||
				$msg == "ich mag dich sehr :)"
			)
			{
				$try = 0;
				do
				{
					$rand = mt_rand(0,2);
				}
				while($this->last == $rand || $try >= 2);
				
				if($rand == 0)
				{
					$this->core->privmsg($event_data->target,$event_data->nick.", ich dich auch :)");
				}
				elseif($rand == 1)
				{
					$this->core->privmsg($event_data->target,$event_data->nick.", aww *-*");
				}
				else
				{
					$this->core->privmsg($event_data->target,$event_data->nick.", wollen wir schmusen? :3");
				}
				$this->last = $rand;
			}
			elseif(
			$msg == "der ist doof, ne?" ||
			$msg == "der ist doof ne?" ||
			$msg == "der ist doof, ne" ||
			$msg == "der ist doof ne"
			)
			{
				$this->core->privmsg($event_data->target,"ja, find ich auch ._.\"");
			}
			elseif(preg_match("/^soll\s+ich\s+(.+)oder\s+(.+(?<!\?))/i", $msg, $matches))
			{
				$answer = mt_rand(0,1);
				if($answer == 0)
				{
					$this->core->privmsg($event_data->target,$event_data->nick.", du solltest ".trim($matches[1])."! :3");
				}
				else
				{
					$this->core->privmsg($event_data->target,$event_data->nick.", du solltest ".trim($matches[2])."! :3");
				}
			}
			elseif(preg_match ("/^was\s+läuft\s+(?:jetzt\s+||(?:um\s+)?([0-9]{2}[\:\.][0-9]{2})\s+)auf\s+([A-Za-z0-9_ß\.\-\s]+)[\?]{0,1}$/i", $msg, $matches))
			{
				$tv = $this->GetTv(trim($matches[2]), trim($matches[1]));
				if ($tv !== false)
				{
					$this->core->privmsg($event_data->target, $event_data->nick.", auf $tv->channel läuft um $tv->time “".$tv->title."”");
				}
				else
				{
					$this->core->privmsg($event_data->target, $event_data->nick.", das weiß ich nicht :(");
				}
			}
			else
			{
				$this->core->privmsg($event_data->target,$event_data->nick.", tut mir leid, das hab ich nicht verstanden.. =(");
			}
		}
	}
}
?>