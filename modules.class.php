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
		$ret = "mod_".str_rot13(md5($ret))."__".$module_name;
		if(isset($this->module_list[$ret]))
		{
			return $this->generateModHash("$$".$module_name);
		}
		else
		{
			return $ret;
		}
	}
	
	public function unloadModule($module_name)
	{
		// lets find the module_id first
		foreach($this->module_list as $module_id => $module)
		{
			if($module->module_name == $module_name)
			{
				break;
			}
		}
		echo $module_id."\n";
		if($this->module_list[$module_id]->module_name == $module_name)
		{
			$events = 0;
			foreach($this->event_list as $key => $event)
			{
				if($event->module_id == $module_id)
				{
					unset($this->event_list[$key]);
					$events++;
				}
			}
			echo "DELETED $events EVENTS!\n";
			unset($this->module_list[$module_id]);
		}
		else
		{
			echo "MODULE NOT LOADED\n";
		}
	}
	
	public function loadModule($module_name)
	{
		echo "LOAD_MODULE: $module_name (";
		if(file_exists("modules/".$module_name.".php"))
		{			
			$mod_hash = $this->generateModHash($module_name);
			echo "$mod_hash)\n";
			
			$content = file_get_contents("modules/".$module_name.".php");
			$content = strtr($content,array('<?php' => '','?>' => '','/*MODULE_ID*/' => $mod_hash));
			eval($content);
			
			$mod = new $mod_hash($this->core);
			$this->module_list[$mod_hash] = (object) array(
				"module_name" => $module_name,
				"module_id" => $mod_hash,
				"ref" => &$mod,
			);
		}
		else
		{
			echo "MODULE-FILE NOT FOUND!)\n";
		}
	}
	
	public function registerEvent($event_name,&$ref)
	{
		echo "REGISTER: $event_name\n";
		$this->event_list[] = (object) array (
			"ref" => &$ref,
			"event_name" => $event_name,
			"module_id" => $ref->module_id,
		);
	}
	
	public function callEvent($event_name,$event_data=array())
	{
		echo "CALL: $event_name\n";
		foreach($this->event_list as $event)
		{
			if($event->event_name == $event_name)
			{
				$this->module_list[$event->module_id]->ref->$event_name($event_data);
			}
		}
	}
}
?>