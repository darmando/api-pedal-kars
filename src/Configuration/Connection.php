<?php
class Connection{
	public $host;
	public $db;
	public $user;
	public $pass;
	
	public function __construct(){
		// $this->host   = "localhost";
		// $this->db 	  = "mafa";
		// $this->user   = "root";
		// $this->pass   = "";

		$this->host   = "174.136.30.158";
		$this->db 	  = "desarr13_bd_pedalkars";
		$this->user   = "desarr13_usrkars";
		$this->pass   = "p3d4lk4rS00#";		
	}


	Public function getConnection(){
		 try {
		    $db = new PDO("mysql:host=".$this->host.";dbname=".$this->db,$this->user,$this->pass);
		    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		    $db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND,'SET NAMES UTF8');
		    return $db;
		  } catch (PDOException $e) {
		    return new PDOException("Error  : " .$e->getMessage());
		  }
	}

} // fin clase


?>
