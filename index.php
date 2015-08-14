<?php
set_time_limit(0);

/**
*	Update your files of project with Edater.
* 
*	@author Erhan Sönmez <erhan.sonmez@hotmail.com.tr>
*	@package Updater
*	@version 0.0.2
*/

class Edater
{
	protected $handle 				= null;
	protected $data 				= null;

	private $host 					= null;

	private $file_settings_path 	= 'updates/version.json';
	private $file_settings 			= null;

	private $downloaded_filename 	= 'test.zip';
	private $downloaded_file_handle = null;

	public $messages 				= array();

	function __construct( $host = 'http//localhost/' )
	{
		$this->host = $host;

		$this->handle = curl_init();
		$this->option( CURLOPT_URL, $this->host );

		if ( is_file( $this->file_settings_path ) )
		{
			$this->file_settings = json_decode( file_get_contents( $this->file_settings_path ) );

			print_r( $this );
			#exit;
		}

		$this->downloaded_file_handle = fopen( $this->downloaded_filename, 'w+' );
	}

	public function option( $type = null, $value = null )
	{
		curl_setopt( $this->handle, $type, $value );
	}

	public function run()
	{
		$this->data = curl_exec( $this->handle );
	}

	public function close()
	{
		curl_close( $this->handle );
	}

	public function download()
	{
		file_put_contents( $this->downloaded_filename, $this->data );
	}

	public function downloadPackages()
	{
		$this->option( CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13' );
		$this->option( CURLOPT_FILE, $this->downloaded_file_handle );
		$this->option( CURLOPT_HEADER, 0 );
		$this->option( CURLOPT_CONNECTTIMEOUT, 5040 );
		$this->option( CURLOPT_RETURNTRANSFER, 1 );
		$this->option( CURLOPT_FOLLOWLOCATION, 1 );
		$this->run();
		$this->close();
		$this->download();
		fclose( $this->downloaded_file_handle );
	}

	public function updatePackages()
	{
		foreach ( $this->file_settings->files as $key => $value )
		{
			$this->createFullPath( $value->destination );
			copy( 'updates'.$value->source.'\\'.$key, 'packages'.$value->destination.'\\'.$key );
		}
	}

	public function openZip()
	{
		$zip = new ZipArchive;
		$res = $zip->open('test.zip');
	    $zip->extractTo('updates');
	    $zip->close();
	}

	public function createFullPath( $path = null )
	{
		$full_path = 'packages';

		if ( !is_dir($full_path) )
		{
			mkdir( $full_path, 0700 );
		}

		foreach ( explode( '\\', $path ) as $key => $value)
		{
			$full_path .= '/'.$value;
			if ( !is_dir($full_path) )
			{
				mkdir( $full_path, 0700 );
			}
		}
	}

	/*
		LEVELS

		x.1.1 - RED, 	High
		1.x.1 - ORANGE, Medium
		1.1.x - GREEN, 	Low

	*/
	public function versionCheck( $current = null )
	{
		$digits = $this->versionDigitsMatch( $current );

		foreach ( $digits as $key => $value )
		{
			if ( $value == 1 )
			{
				switch ( $key )
				{
					case 0:
						$level = 'High';
					break;

					case 1:
						$level = 'Medium';
					break;

					case 2:
						$level = 'Low';
					break;
				}
				$this->messages[] = ' You hava a <strong>'.$level.'</strong> level update. Current is <strong>'.$current.'</strong> and new <strong>'.$this->file_settings->version.'</strong>.';
			}
		}

		print_r( $this->messages );
	}

	public function versionDigitsMatch( $version = null )
	{
		if ( preg_match( '/^[0-9].[0-9].[0-9]$/', $version ) == 1 )
		{
			$current_version = explode( '.', $version );
			$new_version = explode( '.', $this->file_settings->version );

			return array(

				0 => ( $current_version[0]<$new_version[0] ? 1 : 0 ) ,
				1 => ( $current_version[1]<$new_version[1] ? 1 : 0 ) ,
				2 => ( $current_version[2]<$new_version[2] ? 1 : 0 )

			);
		}
		else
		{
			exit( 'Ignored version.' );
		}
	}
}

// Client side
$edater = new Edater( 'http://www.fashionational.com/update/test.zip' );
$edater->downloadPackages();
$edater->openZip();
$edater->versionCheck( '0.0.1' );
#$edater->updatePackages();

?>
