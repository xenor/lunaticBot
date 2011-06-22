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
			if($this->core->online == true)
			{
				$this->core->privmsg($this->core->config->owner,"deleted $events events! unloaded module $module_name!");
			}
		}
		else
		{
			echo "MODULE NOT LOADED\n";
			if($this->core->online == true)
			{
				$this->core->privmsg($this->core->config->owner,"module $module_name not loaded!");
			}
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
			
			if($this->core->online == true)
			{
				$this->core->privmsg($this->core->config->owner,"loaded module $module_name ($mod_hash)");
			}
		}
		else
		{
			echo "MODULE-FILE NOT FOUND!)\n";
			if($this->core->online == true)
			{
				$this->core->privmsg($this->core->config->owner,"wanted to load $module_name, but can't find the file :(");
			}
		}
	}
	
	public function registerEvent($event_name,&$ref)
	{
		if($this->core->online == true)
		{
			$this->core->privmsg($this->core->config->owner,"register event $event_name");
		}
		$this->event_list[] = (object) array (
			//"ref" => &$ref,
			"event_name" => $event_name,
			"module_id" => $ref->module_id,
		);
	}
	
	public function callEvent($event_name,$event_data=array())
	{
		//$this->core->privmsg($this->core->config->owner,"call event $event_name");
		foreach($this->event_list as $event)
		{
			if($event->event_name == $event_name)
			{
				if(isset($this->module_list[$event->module_id]))
				{
					$this->module_list[$event->module_id]->ref->$event_name($event_data);
				}
			}
		}
	}
}
?>