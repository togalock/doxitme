<?php

namespace h;

function cast_date($thing, $cast = null) {
	switch ($cast):
		case "timestamp":
			return strtotime($thing);
		case "date":
			return date("Y-m-d H:i:s", $thing);
		default:
			switch (gettype($thing)):
				case "string":
					return strtotime($thing);
				case "integer":
					return date("Y-m-d H:i:s", $thing);
				default:
					return null;
			endswitch;
	endswitch;
}

function pass() {
	return null;
}

?>