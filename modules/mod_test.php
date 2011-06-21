<?php
class /*RANDOM*/
{
	public $core;
	public function __construct(&$core)
	{
		$this->core = &$core;
		$this->module_id = "/*RANDOM*/";
		$this->core->modules->registerEvent("PRIVMSG",$this);
	}
	public function PRIVMSG($event_data)
	{
		print_r($event_data);
	}
}
?>