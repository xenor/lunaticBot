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
		if($this->conn != false && !feof($this->conn))
		{
			echo "[>>>]: ".$str."\r\n";
			fputs($this->conn,trim($str)."\r\n");
		}
		else
		{
			die("Not connected!");
		}
	}
	
	public function recv()
	{
		if($this->conn == false || feof($this->conn))
		{
			die("Not connected!");
		}
		while(($str = trim(fgets($this->conn))) != "")
		{
			if(feof($this->conn))
			{
				die("Not connected!");
			}
			echo "[<<<]: ".$str."\r\n";
			return $str;
		}
	}
}
?>