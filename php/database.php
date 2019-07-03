<?php

namespace d;

// Default values
const SERVER = 'localhost';
const USERNAME = 'interface';
const PASSWORD = 'RANDOM_PASS';
const NAME = 'doxitme';
const PORT = 5703;


class Database {
	
	public $db;
	private $server, $username, $password, $name, $port;
	
	public function __construct($server = SERVER,
								$username = USERNAME, 
								$password = PASSWORD, 
								$name = NAME,
								$port = PORT) {
		$this->server = $server;
		$this->username = $username;
		$this->password = $password;
		$this->name = $name;
		$this->port = $port;
		
		$this->db = new \mysqli($server, $username, $password, $name, $port);
		if ($this->db->connect_errno > 0):
			die("Unable to connect: " . $this->db->connect_error);
		endif;
	}
	
	public function query($query, $count = null) {
		
		# Null = Return Result Stream
		# Integer = Return N assocs
		# True = Return If has object
		# False = Return If empty
		
		if (!$result = $this->db->query($query)):
			return null;
		endif;
		
		if (gettype($count) == "integer"):
			if ($result->num_rows >= $count):
				$res = array();
				for ($i = 1; $i <= $count; $i++):
					array_push($res, $result->fetch_assoc());
				endfor;
				return $res;
			else:
				return False;
			endif;
		
		elseif (is_null($count)):
			return $result;
		
		elseif (gettype($count) == "boolean"):
			return $count == $result->num_rows > 0;
		
		else:
			return False;
		
		endif;
	}
	
	public function insert($table, $assoc) {
		$keys = implode(",", array_keys($assoc));
		$values = implode(",", array_values($assoc));
		$query = "INSERT INTO $table ($keys) VALUES ($values)";
		if ($result = $db->query($query)):
			return True;
		else:
			return False;
		endif;
	}
	
	public function update($table, $id_key, $id_value, $assoc) {
		$id_value = is_string($id_value) ? "'$id_value'" : $id_value;
		$pairs = array();
		foreach ($assoc as $key => $value):
			if ($key != $id_key):
				array_push($pairs, "$key = $value");
			endif;
		endforeach;
		$combined_pairs = implode(",", $pairs);
		$query = "UPDATE $table SET $combined_pairs WHERE $id_key = $id_value";
		if ($result = $db->query($query)):
			return True;
		else:
			return False;
		endif;
	}
	
}

?>