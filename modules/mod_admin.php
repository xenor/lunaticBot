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
		if($event_data->nick == $this->core->config->owner && $this->core->match_host($event_data->host,$this->core->config->owner_host))
		{
			$cmd = explode(' ',$event_data->message);
			if($cmd[0] == "unload")
			{
				$this->core->privmsg($this->core->config->owner,"unloading module ".$cmd[1]);
				$this->core->modules->unloadModule($cmd[1]);
			}
			elseif($cmd[0] == "load")
			{
				$this->core->privmsg($this->core->config->owner,"loading module ".$cmd[1]);
				$this->core->modules->loadModule($cmd[1]);
			}
			elseif($cmd[0] == "reload")
			{
				$this->core->privmsg($this->core->config->owner,"reloading module ".$cmd[1]);
				$this->core->modules->unloadModule($cmd[1]);
				$this->core->modules->loadModule($cmd[1]);
			}
			elseif($cmd[0] == "rehash")
			{
				include("config.php");
				$this->core->config = &$config;
				$this->core->privmsg($this->core->config->owner,"Successfully rehashed configuration file!");
			}
		}
	}
}
?>