<?php
class modules
{
	public $core;
	public $module_list;
	public $event_list;
	
	public function __construct(&$core)
	{
		$this->core = &$core;
		$this->module_list = array();
		$this->event_list = array();
	}
	
	public function generateModHash($module_name)
	{
		$ret = "42";
		$ret = $module_name."&&&".microtime();
		$ret = sha1($ret).time();
		$ret = "mod_".str_rot13(md5($ret));
		if(isset($this->module_list[$ret]))
		{
			return $this->generateModHash($module_name."%%!");
		}
		else
		{
			return $ret;
		}
	}
	
	public function loadModule($module_name)
	{
		echo "LOAD: $module_name (";
		if(file_exists($module_name))
		{
			$content = file_get_contents($module_name);
			$mod_hash = $this->generateModHash($module_name);
			echo "$mod_hash)\n";
			$content = strtr($content,array('<?php' => '','?>' => '','/*RANDOM*/' => $mod_hash));
			eval($content);
			$mod = new $mod_hash($this->core);
			$this->module_list[$mod_hash] = &$mod;
		}
		else
		{
			echo "MODULE-FILE NOT FOUND!\n";
		}
	}
	
	public function registerEvent($event_name,&$ref)
	{
		echo "REGISTER: $event_name\n";
		$this->event_list[] = (object) array (
			"ref" => &$ref,
			"module_id" => $ref->module_id,
		);
	}
	
	public function callEvent($event_name,$event_data=array())
	{
		echo "CALL: $event_name\n";
		foreach($this->event_list as $event)
		{
			$this->module_list[$event->module_id]->$event_name($event_data);
		}
	}
}
?>