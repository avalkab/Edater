<?php namespace lib\Request\Secure;

use \lib\Session\Session as Session,
	\lib\Request\Request as Request,
	\lib\Wujh\Wujh as Wujh;

class Secure
{
	public static $key;

	public static function active( $key = null, $name = null )
	{
		$hash = Wujh::encode($key);
		$session_name = !is_null($name) ? $name : 'token';
		Session::set($session_name, $hash);
		return $hash;
	}

	public static function valid( $name = null, $req_name = null )
	{
		$session_name = !is_null($name) ? $name : 'token';
		$request_name = !is_null($req_name) ? $req_name : 'token';

		try
		{
			if (Session::get($session_name) == Request::post($request_name))
			{
				/*if (Wujh::check($key, Session::get('token')) === 0){
					throw new \Exception('Token eşleşme hatası.');
				}else{ */
					throw new \Exception(1);
				#}
			}
			else
			{
				throw new \Exception('Invalid token.');
			}
		} catch (\Exception $e)
		{
			$result = $e->getMessage();
		}
		finally
		{
			if ($result == 1) {
				return true;
			}else{
				return false;
			}
		}
	}
}
?>