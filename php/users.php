<?php

namespace u;


# For testing only
# include "database.php";


class UserDB {
	
	public $db;
	
	public function __construct($db) {
		if ($db->query("SELECT * FROM users LIMIT 0")):
			$this->db = $db;
		endif;
	}
	
	# TODO: finish this
}


class ProfileDB {
	
	public $db;
	
	public function __construct($db) {
		if ($db->query("SELECT * FROM profile_data LIMIT 0")):
			$this->db = $db;
		endif;
	}
	
	# TODO: finish this
	
}

?>