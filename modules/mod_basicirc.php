<?php
class /*MODULE_ID*/
{
	public $core;
	public function __construct(&$core)
	{
		$this->core = &$core;
		$this->module_id = "/*MODULE_ID*/";
		$this->core->modules->registerEvent("GENERIC",$this);
	}
	public function GENERIC($event_data)
	{
		if($event_data->commands[0] == "PING")
		{
			$this->core->connection->send("PONG ".$event_data->commands[1]);
		}
		elseif($event_data->commands[1] == 376)
		{
			$this->core->online = true;
		}
	}
}
?>