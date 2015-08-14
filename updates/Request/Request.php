<?php namespace lib\Request;

use \lib\Session\Session as Session,
	\lib\Wujh\Wujh as Wujh;

class Request
{
	public static function takeIt($type = null, $key = null){

		switch ($type) {
			case 'GET':
				$result = is_null($key) ? $_GET : $_GET[$key];
			break;
			case 'POST':
				$result = is_null($key) ? $_POST : $_POST[$key];
			break;
			case 'REQUEST':
				$result = is_null($key) ? $_REQUEST : $_REQUEST[$key];
			break;
			case 'FILE':
				$result = is_null($key) ? $_FILES : $_FILES[$key];
			break;
			default:
				exit('No such a type.');
			break;
		}
		return $result;
	}

	public static function get($key = null){
		return self::takeIt('GET', $key);
	}

	public static function post($key = null){
		return self::takeIt('POST', $key);
	}

	public static function req($key = null){
		return self::takeIt('REQUEST', $key);
	}

	public static function file($key = null){
		return self::takeIt('FILE', $key);
	}

	public static function validMethod($require_method = 'POST'){
		return ($_SERVER['REQUEST_METHOD'] == $require_method) ? 1 : 0;
	} 
}
?>