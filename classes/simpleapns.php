<?php

namespace SimpleAPNS;

class SimpleAPNS
{

	public static $_instance;

	protected static $_certs;

	protected static $_apnsHost;

	private function __construct() {}

	public static function forge($config = null)
	{

		if(!isset(self::$_instance))
		{
			self::$_instance = new static();
		}

		\Config::load('simpleapns',true);
		static::$_certs = is_array($config) && isset($config['certificates']) ? $config['certificates'] : \Config::get('simpleapns.defaults.certificates');
		static::$_apnsHost = is_array($config) && isset($config['apns_host']) ? $config['apns_host'] : \Config::get('simpleapns.apns_host');

		return self::$_instance;
		
	}

	protected static function create_stream($payload,$device_token)
	{
		$payload = json_encode($payload);

		$streamContext = stream_context_create();
		stream_context_set_option($streamContext,'ssl','local_cert',static::$_certs[\Fuel::$env]);

		$apns = stream_socket_client('ssl://'.static::$_apnsHost[\Fuel::$env],$errorno,$errorstr,2,STREAM_CLIENT_CONNECT,$streamContext);

		if(!$apns)
		{
			unset($apns);

			return FALSE;
		}

		$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $device_token)) . chr(0) . chr(strlen($payload)) . $payload;

		if(fwrite($apns,$apnsMessage))
		{
			fclose($apns);
			unset($apns);

			return TRUE;		
		}		
	}

	public static function send($payload,$device_token)
	{

		return static::create_stream($payload,$device_token);
	}


}