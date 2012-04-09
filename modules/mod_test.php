<?php
class /*MODULE_ID*/
{
	public $core;
	public function __construct(&$core)
	{
		$this->core = &$core;
		$this->module_id = "/*MODULE_ID*/";
		$this->core->modules->registerEvent("PRIVMSG",$this);
		$this->core->privmsg("#crapcode-dev", "Danke ePirat! <3 :)");
	}
	public function PRIVMSG($event_data)
	{
		print_r($event_data);
	}
}
?>