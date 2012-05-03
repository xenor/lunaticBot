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
		print_r($event_data);
	}
}
?>