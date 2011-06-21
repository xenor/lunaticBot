<?php

ini_set("display_errors",1);
set_time_limit(0);

require_once "config.php";
require_once "connection.class.php";
require_once "core.class.php";
require_once "modules.class.php";

$core = new core($config);
$core->modules = new modules($core);
$core->modules->loadModule("modules/mod_test.php");

while(true)
{
	$str = $core->recv();
	$str_array = explode("\r\n", $str);
	foreach($str_array as $line)
	{
		$core->handle($line);
	}
}
?>