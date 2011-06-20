<?php
class connection
{
	public $config;
	public function __construct(&$config)
	{
		$this->config = &$config;
		$this->conn = fsockopen($config->host,$config->port);
	}
	
	public function send($str)
	{
		echo "[>>>]: ".$str."\r\n";
		fputs($this->conn,trim($str)."\r\n");
	}
	
	public function recv()
	{
		while(($str = trim(fgets($this->conn))) != "")
		{
			echo "[<<<]: ".$str."\r\n";
			return $str;
		}
	}
}
?>