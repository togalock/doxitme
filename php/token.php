<?php

namespace t;
include "helpers.php";


class Token {
	
	public $token, $token_type, $target_type, $target_id, $expires_at, $created_at;
	const KEYS = ["token", "token_type", "target_type", "target_id", "expires_at", "created_at"];
	const DEFAULT_EXPIRY = 3 * 24 * 60 * 60;
	
	public function __construct($token,
								$token_type = null, 
								$target_type = null,
								$target_id = null,
								$expires_at = null
								) {
		
		$created_at = time();
		
		foreach(self::KEYS as $key) {
			if (is_null(${$key}) or self::validate_item($key, ${$key})):
				$this->{$key} = ${$key};
			endif;
		}
		
	}
	
	public static function validate_item($key, $value) {
		switch ($key):
			case "token":
				return ctype_alnum($value) and strlen($value) == 30;
			case "token_type":
				return in_array($value, ["LOG", "RES", "VER"]);
			case "target_type":
				return in_array($value, ["USER", "PROFILE"]);
			case "target_id":
			case "expires_at":
			case "created_at":
				return is_int($value);
			default:
				return False;
		endswitch;
	}
	
	public function sqlize() {
		
		$assoc = array();
		
		foreach (self::KEYS as $key) {
			if (!is_null($this->{$key})):
				if (is_string($this->{$key})):
					$assoc[$key] = "'{$this->{$key}}'";
				else:
					$assoc[$key] = $this->{$key};
				endif;
			endif;
		}
		
		if (!is_null($assoc["created_at"])):
			$date = \h\cast_date($assoc["created_at"], "date");
			$assoc["created_at"] = "'$date'";
		endif;
		
		if (!is_null($assoc["expires_at"])):
			$date = \h\cast_date($assoc["expires_at"], "date");
			$assoc["expires_at"] = "'$date'";
		endif;
		
		return $assoc;
	}
	
}


class TokenDB {
	
	public $db;
	const VALID_CHARS = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	const TOKEN_LENGTH = 30;
	
	public function __construct($db) {
		if ($db->query("SELECT * FROM tokens LIMIT 0")):
			$this->db = $db;
		endif;
	}
	
	public static function generate_token() {
		$token = "";
		
		for ($char = 1; $char <= self::TOKEN_LENGTH; $char++):
			$token .= self::VALID_CHARS[random_int(0, strlen(self::VALID_CHARS) - 1)];
		endfor;
		
		return $token;
		
	}
	
	public function get_new_token() {
		
		for ($attempt = 1; $attempt <= 5; $attempt++):
			$token = self::generate_token();
		
			if ($this->db->query("SELECT token FROM tokens WHERE token = '$token' LIMIT 1", False)):
				return $token;
			endif;
		
		endfor;
		
		return null;
	}
	
	public function commit($token) {
		if ($this->db->query("SELECT token FROM tokens WHERE token = '{$token->token}' LIMIT 1", False)):
			$this->db->insert("tokens", $token->sqlize());
		else:
			$this->db->update("tokens", "token", "'{$token->token}'", $token->sqlize());
		endif;
	}
}

?>