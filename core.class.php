<?php
class core
{
	public function __construct(&$config)
	{
		$connection = new connection($config);
		
		$this->config = &$config;
		$this->connection = &$connection;
		
		$connection->send("USER ".$config->user." * * :".$config->realname);
		$connection->send("NICK ".$config->nick);
	}
	
	public function recv()
	{
		do $str = $this->connection->recv(); while($str == "");
		return $str;
	}
	
	public function handle($str)
	{
		$cmd = explode(' ',$str);
		if($cmd[1] == "PRIVMSG")
		{
			$nick = substr($cmd[0],1,strpos($cmd[0],"!")-1);
			$ident = substr($cmd[0],strpos($cmd[0],"!")+1,strpos($cmd[0],"@")-strlen($nick)-2);
			$host = substr($cmd[0],strpos($cmd[0],"@")+1);
			
			$msg = substr($str,strlen($cmd[0].$cmd[1].$cmd[2])+4);
			
			echo "Nick: $nick\n";
			echo "Ident: $ident\n";
			echo "Host: $host\n";
			echo "Message: $msg\n";
			
		}
		elseif($cmd[1] == "INVITE")
		{
			$channel = substr($cmd[3],1);
			$this->join($channel);
		}
		elseif($cmd[1] == "376")
		{
			foreach($this->config->channels as $channel)
			{
				$this->join($channel);
			}
		}
		elseif($cmd[0] == "PING")
		{
			$this->connection->send("PONG ".$cmd[1]);
		}
	}
	
	public function join($channel)
	{
		$this->connection->send("JOIN $channel");
	}
}
?>