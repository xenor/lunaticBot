<?php

ini_set("display_errors",1);
set_time_limit(0);

require_once "config.php";
require_once "connection.class.php";
require_once "core.class.php";
require_once "modules.class.php";

$core = new core($config);
$core->modules = new modules($core);
$core->modules->loadModule("mod_pong");
$core->modules->loadModule("mod_autojoin");
$core->modules->loadModule("mod_admin");
$core->modules->loadModule("mod_test");
$core->modules->loadModule("mod_speak");

while(!feof($core->connection->conn))
{
	$str = $core->recv();
	$str_array = explode("\r\n", $str);
	foreach($str_array as $line)
	{
		$core->handle($line);
	}
}
?>