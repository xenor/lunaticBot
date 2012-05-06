<?php
class core
{
	private $query = array();
	public $online = false;

	public function __construct(&$config)
	{
		$connection = new connection($config);
		
		$this->config = &$config;
		$this->connection = &$connection;
		
		$connection->send("USER ".$config->user." * * :".$config->realname);
		$connection->send("NICK ".$config->nick);
	}

	public function quee($add=false)
	{
		if ($add !== false){
			$this->query[] = $add;
			return;
		} else {
			foreach($this->query as $msg){
				$this->connection->send($msg);
			}
		}
	}

	public function recv()
	{
		do
		{
			$str = $this->connection->recv();
		} while($str == "");
		echo "[<<<]: ".$str."\r\n";
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
			/*
			echo "Nick: $nick\n";
			echo "Ident: $ident\n";
			echo "Host: $host\n";
			echo "Message: $msg\n";
			*/
			$event_data = (object) array(
				"nick" => $nick,
				"ident" => $ident,
				"host" => $host,
				"target" => $cmd[2],
				"message" => $msg,
			);
			
			$this->modules->callEvent("PRIVMSG",$event_data);
		}


		$event_data = (object) array(
			"string" => $str,
			"commands" => $cmd,
		);
		
		$this->modules->callEvent("GENERIC",$event_data);
		
		if(intval($cmd[1]) != 0)
		{
			$event_data = (object) array(
				"numeric" => intval($cmd[1]),
				"string" => $str,
				"commands" => $cmd,
			);
			$this->modules->callEvent("NUMERIC",$event_data);
		}
	}
	
	public function match_host($original,$match_this)
	{
		/*$pattern = "/".str_replace($match_this,"*","(.*)")."/i";
		echo "matching pattern $pattern to $original\n";
		$match = array();
		$matches = preg_match($pattern,$original,$match);
		if($matches >= 1) return true; else return false;*/
		return true;
	}
	
	public function join($channel)
	{
		$this->connection->send("JOIN $channel");
	}
	
	public function part($channel)
	{
		$this->connection->send("PART $channel");
	}

	public function privmsg($target,$msg)
	{
		if ($this->online !== false){
			$this->connection->send("PRIVMSG $target :$msg");
		} else {
			$this->quee("PRIVMSG $target :$msg");
		}
	}
}
?>