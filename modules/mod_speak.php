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
			elseif(substr($msg,0,strlen("soll ich")) == "soll ich")
			{
				$sep = strpos($msg,"oder");
				$answer1 = substr($msg,strlen("soll ich "),$sep-strlen("soll ich "));
				$answer2 = substr($msg,$sep+5);
				$answer1 = substr($answer1,0,strlen($answer1)-1);
				$answer2 = substr($answer2,0,strlen($answer2)-1);
				$answer = mt_rand(0,1);
				if($answer == 0)
				{
					$this->core->privmsg($event_data->target,$event_data->nick.", du solltest $answer1! :3");
				}
				else
				{
					$this->core->privmsg($event_data->target,$event_data->nick.", du solltest $answer2! :3");
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