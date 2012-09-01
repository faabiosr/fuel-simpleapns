<?php

return array(

	'defaults' => array(

		'certificates'	=> array(
			'development'	=> APPPATH.'certificates/dev.pem',
			'production'	=> APPPATH.'certificates/prod.pem'
		)
		
	),
	'apns_host' => array(
		'development'	=> 'gateway.sandbox.push.apple.com:2195',
		'production'	=> 'gateway.push.apple.com:2195'
	)
);