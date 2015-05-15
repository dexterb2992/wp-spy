<?php
define( 'WPSPY_HOST', dirname( dirname(__FILE__) ).'/wp-spy_/' );

define('BASE_URL',   dirname( dirname(__FILE__) )."/" );

# Get the name of the current folder
preg_match('~/(.*?)/~', $_SERVER['SCRIPT_NAME'], $output);
define('FOLDER_NAME', '/'.$output[1].'/');	

#Do not forget to add trailing slash at the end.
define('ABS_PATH', dirname( dirname(__FILE__) ).'/wp-spy_/' );

define('BASE_PATH', BASE_URL.FOLDER_NAME);

#This folder contains system files
define('APP_FOLDER', ABS_PATH.'app/');

#Class Folder, Do not change. Ask for guidance.
define('CLASS_FOLDER', APP_FOLDER.'classes/');

#Database settings
$GLOBALS['CFG']=array(
					
	'Database' => array(
		'host'=>'localhost',
		'databasename' =>'dexterb',
		'username' =>'root',
		'password' =>'',
		'prefix' => 'wp_'
	)
);


