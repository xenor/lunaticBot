<?php
class /*MODULE_ID*/
{
	public $core;
	public function __construct(&$core)
	{
		$this->core = &$core;
		$this->module_id = "/*MODULE_ID*/";
		$this->core->modules->registerEvent("PRIVMSG",$this);
	}
	public function PRIVMSG($event_data)
	{
		if(substr($event_data->message,0,strlen($this->core->config->nick)) == $this->core->config->nick)
		{
			$msg = substr($event_data->message,strlen($this->core->config->nick) + 2);
			if(
				$msg == "was kommt im tv?" ||
				$msg == "was kommt im fernsehen?" ||
				$msg == "was läuft im tv?" ||
				$msg == "was läuft im fernsehen?" ||
				$msg == "was gibts in der glotze?" ||
				$msg == "was kommt im tv" ||
				$msg == "was kommt im fernsehen" ||
				$msg == "was läuft im fernsehen" ||
				$msg == "was läuft im tv"
			){
				$feed = new SimpleXMLElement("http://www.tvspielfilm.de/tv-programm/rss/jetzt.xml", 0, true);
				foreach($feed->item as $item){
					$item->title = explode(" | ", $item->title);
					$item->title[0] //time
					$item->
					print $item->title;
				}
			}
		}
	}
}
?>