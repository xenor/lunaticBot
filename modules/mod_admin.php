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
		if(in_array($event_data->nick,$this->core->config->owner) /* && $this->core->match_host($event_data->host,$this->core->config->owner_host) */)
		{
		
			$ownerNick = $event_data->nick;
		
			$cmd = explode(' ',$event_data->message);
			if($cmd[0] == "unload")
			{
				$this->core->privmsg($ownerNick,"unloading module ".$cmd[1]);
				$this->core->modules->unloadModule($cmd[1]);
			}
			elseif($cmd[0] == "load")
			{
				$this->core->privmsg($ownerNick,"loading module ".$cmd[1]);
				$this->core->modules->loadModule($cmd[1]);
			}
			elseif($cmd[0] == "reload")
			{
				$this->core->privmsg($ownerNick,"reloading module ".$cmd[1]);
				$this->core->modules->unloadModule($cmd[1]);
				$this->core->modules->loadModule($cmd[1]);
			}
			elseif($cmd[0] == "rehash")
			{
				include("config.php");
				$this->core->config = &$config;
				$this->core->privmsg($ownerNick,"Successfully rehashed configuration file!");
			}
			elseif($cmd[0] == "join")
			{
				$this->core->join($cmd[1]);
			}
			elseif($cmd[0] == "part")
			{
				$this->core->part($cmd[1]);
			}
		}
	}
}
?>