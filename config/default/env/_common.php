<?php

# 
//define("_APP_CONTEXT", 					!getenv("APP_CONTEXT") ? 'prod' : getenv("APP_CONTEXT"));

# Try to get the server domain (or use the IP as the domain if it hasn't)
define("_HOST_IS_IP", 					filter_var($_SERVER['HTTP_HOST'], FILTER_VALIDATE_IP));
define("_DOMAIN", 						 _HOST_IS_IP ? $_SERVER['HTTP_HOST'] : preg_replace('/(.*\.)?(.*\..*)/', '$2', $_SERVER['SERVER_NAME']));

# Try to get the server subdomain (if not an ip)
//define("_SUBDOMAIN", 					str_replace('.' . _DOMAIN, '', $_SERVER['HTTP_HOST']));
define("_SUBDOMAIN", 					_HOST_IS_IP ? '' : str_replace('.' . _DOMAIN, '', $_SERVER['HTTP_HOST']));

# Get the projet full path on the server
//define("_PATH",							getcwd() . '/'); // does not return the expected path when called via CLI
define("_PATH",							realpath((dirname(realpath(__FILE__))) . '/../../../') . '/'); // 

# Get app name using base project folder name
define("_APP_NAME", 					basename(_PATH));

# Get path relatively to server root
define("_PATH_REL", 					str_replace($_SERVER['DOCUMENT_ROOT'], '', _PATH));

# Get used scheme (http or https)
define("_APP_PROTOCOL", 				'http' . ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '' ) . '://');

// If a server name has been defined, use it
// Otherwise, use the server ip and the project base folder path as the base URL 						
define("_URL", 							_APP_PROTOCOL . $_SERVER['HTTP_HOST'] . rtrim(_PATH_REL, '/') . '/');
define("_URL_REL", 						'/' . _PATH_REL);
define("_URL_STATIC", 					_APP_PROTOCOL . 'static.' . _DOMAIN . '/');
define("_URL_STATIC_1", 				_APP_PROTOCOL . 'static1.' . _DOMAIN . '/');


# DATABASE PARAMETERS
define("_DB_SYSTEM",   					'mysql'); 			// mysql, postgresql, sqlite, mongodb, oracle, mssql
define("_DB_DRIVER",    				'default'); 		// 'default', 'pdo' (for mysql, postresql, sqlite, mssql, orace), 'mysqli' (for mysql)   
define("_DB_HOST",    					'localhost'); 		//
define("_DB_USER",      				'admin'); 			//
define("_DB_PASSWORD",  				'F4K3paSSw0rD'); 	//
define("_DB_NAME",  					_APP_NAME); 		//
define("_DB_PORT",  					'3306'); 			// usual ports: mysql=3306, postgresql=5432, sqlite:80, mongodb=27017, oci=1521
define('_DB_TABLE_PREFIX', 				''); 				//
define('_DB_CONNECTION_TIMEOUT', 		5); 				// In seconds. Better to set this in your php ini. let empty to use php.ini config (mysql=60)

# FTP PARAMETERS
define("_FTP_HOST",    					'localhost');
define("_FTP_USER_NAME",    			'userftp');
define("_FTP_USER_PASSWORD",    		'F4K3paSSw0rD');
define("_FTP_PORT",    					21);
define("_FTP_ROOT",    					'/');


// Classes autoloading
function __autoload($className)
{
	$first 		= $className[0]; 													// Get first letter
	
	$is2ndUp 	= $className[1] === strtoupper($className[1]); 						// Check if second is uppercased
	
if ( $first === 'C' && $is2ndUp ) { return; } 
	
	$known 		= array('C' =>'controller'); 										// Known classes types
	$type 		= isset($known[$first]) && $is2ndUp ? $known[$first] : 'lib'; 		// Set class type
	$path 		= constant('_PATH_' . strtoupper($type  . 's')); 					// Get class type base path
	$file 		= $path . $className . '.class.php'; 								// Get class filepath

var_dump(__METHOD__);
//var_dump($className);
//var_dump($file);
	
	class_exists($className) || (file_exists($file) && require($file));
}
spl_autoload_register('__autoload');


# Security
ini_set('session.cookie_httponly', 	1);
ini_set('session.cookie_secure', 	_APP_PROTOCOL === 'https' ? 1 :0 ); // Only active this when used with https 


// Force timezone to UTC
// TODO: handle this properly (allow user to choose its tz)
//$old = date_default_timezone_get();
date_default_timezone_set('UTC');


?>