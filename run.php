<?php

ini_set("display_errors",1);
set_time_limit(0);

require_once "config.php";
require_once "connection.class.php";
require_once "core.class.php";

$core = new core($config);

while($str = $core->wait())
{
	$str_array = explode("\r\n",$str);
	foreach($str_array as $line)
	{
		$core->handle($line);
	}
}
?>