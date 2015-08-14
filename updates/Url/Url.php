<?php namespace lib\Url;

use \lib\Slug\Slug as Slug;

class Url
{
	private static $url_list;

	public static function setUrls()
	{
		self::$url_list = require_once(__ROOT . 'app/config/urls.php');
	}

	public static function make($type = null, Array $parameters = null)
	{
		if (array_key_exists($type, self::$url_list)) {
			$url = str_replace(array_keys($parameters), array_values($parameters), self::$url_list[$type]);
			echo str_replace(array('{','}'),'',$url);
		}else{
			echo '<strong>'.$type . '</strong> isimde tanımlı bir url anahtarı bulunmuyor. Lütfen <strong>app/config/<font color="blue">urls.php</font></strong> dosyasını kontrol edin.';
		}
	}
}

Url::setUrls();

?>