<?php
ini_set("display_errors",1);
set_time_limit(0);

require_once "config.php";
require_once "connection.class.php";
require_once "core.class.php";
require_once "modules.class.php";

$core = new core($config);
$core->modules = new modules($core);
foreach(scandir("./modules") as $moduleFilename)
{
	if($moduleFilename != ".." && $moduleFilename != "." && substr($moduleFilename,0,4) == "mod_")
	{
		$core->modules->loadModule(substr($moduleFilename,0,strlen($moduleFilename)-4));
	}
}
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