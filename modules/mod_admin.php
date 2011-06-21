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
		$command = explode(' ',$event_data->message);
		if($command[0] == "unload")
		{
			$this->core->connection->send("PRIVMSG #crapcode-dev :unloading module ".$command[1]);
			$this->core->modules->unloadModule($command[1]);
		}
		elseif($command[0] == "load")
		{
			$this->core->connection->send("PRIVMSG #crapcode-dev :loading module ".$command[1]);
			$this->core->modules->loadModule($command[1]);
		}
	}
}
?>