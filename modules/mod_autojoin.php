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
		$cmd = $event_data->commands;
		if($cmd[1] == "376")
		{
			foreach($this->core->config->channels as $channel)
			{
				$this->core->join($channel);
			}
		}
		elseif($cmd[1] == "INVITE")
		{
			$channel = substr($cmd[3],1);
			$this->core->join($channel);
		}
	}
}
?>