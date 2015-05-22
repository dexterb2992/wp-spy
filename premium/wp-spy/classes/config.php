<?php
include( dirname( dirname ( dirname( dirname( dirname(__FILE__) ) ) ) )."/wp-config.php" );
define( 'WPSPY_HOST', plugins_url('wp-spy/wp-spy_/') );

define('BASE_URL',  plugins_url("wp-spy/"));

# Get the name of the current folder
preg_match('~/(.*?)/~', $_SERVER['SCRIPT_NAME'], $output);
define('FOLDER_NAME', '/'.$output[1].'/');	

#Do not forget to add trailing slash at the end.
define('ABS_PATH', dirname( dirname ( dirname( dirname( dirname(__FILE__) ) ) ) ).'/wp-content/plugins/wp-spy/wp-spy_/');

define('BASE_PATH', BASE_URL.FOLDER_NAME);

#This folder contains system files
define('APP_FOLDER', ABS_PATH.'app/');

#Class Folder, Do not change. Ask for guidance.
define('CLASS_FOLDER', APP_FOLDER.'classes/');


