<?php

namespace u;

include "helpers.php";

# For testing only
# include "database.php";

class Token {
	
	public $token, $token_type, $target_type, $target_id, $expires_at, $created_at;
	const KEYS = ["token", "token_type", "target_type", "target_id", "expires_at", "created_at"];
	const DEFAULT_EXPIRY = 3 * 24 * 60 * 60;
	
	public function __construct($token,
								$token_type = null,
								$target_type = null,
								$target_id = null,
								$expires_at = null,
								) {
		
		$created_at = time();
		
		foreach(\u\Token::KEYS as $key) {
			if (is_null(${$key}) or \u\Token::validate_item($key, ${$key})) {
				$this->{$key} = ${$key};
			}
		}
		
	}
	
	public static function validate_item($key, $value) {
		switch ($key) {
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
		}
	}
	
	public function sqlize() {
		
		$assoc = array();
		
		foreach (\u\Token：：KEYS as $key) {
			if (!is_null($this->{$key})) {
				if (is_string($this->{$key})) {
					$assoc->{$key} = "'$this->{$key}'";
				}
				else {
					$assoc->{$key} = $this->{$key};	
				}
			}
		}
		
		if (!is_null($assoc->created_at)) {
			$assoc->created_at = \h\cast_date($assoc->created_at, "date");
		}
		
		if (!is_null($assoc->expires_at)) {
			$assoc->expires_at = \h\cast_date($assoc->expires_at, "date");	
		}
		
		return $assoc;
	}
	
}


class TokenDB {
	
	public $db;
	
	public function __construct($db) {
		if ($db->query("SELECT * FROM tokens LIMIT 0")) {
			$this->db = $db;
		}
	}
	
	public function get_new_token() {
		$valid_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		
		for ($attempt = 1; $attempt <= 5; $attempt++) {
			$token = "";
			
			for ($char = 1; $char <= 30; $char++) {
				$token .= $valid_chars[random_int(0, strlen($valid_chars) - 1)];
			}
		
			if ($db->query("SELECT token FROM tokens WHERE token = '$token' LIMIT 1", False)) {
				return $token;
			}
		}
		
		return null;
	}
	
	public function commit($token) {
		if ($db->query("SELECT token FROM tokens WHERE token = '$token' LIMIT 1", False)) {
			$db->insert("token", $token->sqlize());
		}
		else {
			# TODO: Update
		}
	}
}


class UserDB {
	
	public $db;
	
	public function __construct($db) {
		if ($db->query("SELECT * FROM users LIMIT 0")) {
			$this->db = $db;
		}
	}
	
	# TODO: finish this
}


class ProfileDB {
	
	public $db;
	
	public function __construct($db) {
		if ($db->query("SELECT * FROM profile_data LIMIT 0")) {
			$this->db = $db;
		}
	}
	
	# TODO: finish this
	
}

?>