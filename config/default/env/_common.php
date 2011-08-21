<?php

# 
define("_APP_CONTEXT", 					!getenv("APP_CONTEXT") ? 'prod' : getenv("APP_CONTEXT"));

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
//define("_URL", 							_APP_PROTOCOL . ( $_SERVER['SERVER_NAME'] !== $_SERVER['SERVER_ADDR'] ? $_SERVER['SERVER_NAME'] . '/' : $_SERVER['SERVER_ADDR'] . _PATH_REL ));						
define("_URL", 							_APP_PROTOCOL . $_SERVER['HTTP_HOST'] . rtrim(_PATH_REL, '/') . '/');
//define("_URL_REL", 						$_SERVER['SERVER_NAME'] !== $_SERVER['SERVER_ADDR'] ? '/' : _PATH_REL );
define("_URL_REL", 						'/' . _PATH_REL);
define("_URL_STATIC", 					_APP_PROTOCOL . 'static.' . _DOMAIN . '/');
define("_URL_STATIC_1", 				_APP_PROTOCOL . 'static1.' . _DOMAIN . '/');

//var_dump($_SERVER['DOCUMENT_ROOT']);
//var_dump($_SERVER['SERVER_ADDR']);
//var_dump($_SERVER['SERVER_NAME']);
//var_dump(_SUBDOMAIN);
//var_dump(_PATH);
//var_dump(_PATH_REL);
//var_dump(_URL);
//phpinfo();
//die();

# DATABASE PARAMETERS
define("_DB_SYSTEM",   					'mysqli'); 			// mysql, mysqli, postgresql, sqlite, mongodb, pdomysql
define("_DB_HOST",    					'localhost'); 		//
define("_DB_USER",      				'admin'); 			//
define("_DB_PASSWORD",  				'F4K3paSSw0rD'); 	//
define("_DB_NAME",  					_APP_NAME); 		//
define("_DB_PORT",  					'3306'); 			// mysql:3306 , postgresql: 5432, sqlite:
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
    // Get first & second letter and check if second is uppercased
	$first 			= $className[0];
	$secondIsUpper 	= $className[1] === strtoupper($className[1]);
	// Known classes types
	//$known 		= array('M' => 'model', 'V' => 'view', 'C' =>'controller');
	$known 			= array('C' =>'controller');
	$type 			= isset($known[$first]) && $secondIsUpper ? $known[$first] : 'lib';
	$path 			= constant('_PATH_' . strtoupper($type  . 's'));
	$file 			= $path . $className . '.class.php';
	
//var_dump($file);
	
	class_exists($className) || (file_exists($file) && require($file));
}
spl_autoload_register('__autoload');


// Deprecate???
$env = array(
	'name' 	=> _APP_CONTEXT,
	//'type' => in_array(_APP_CONTEXT, array('local','dev')) && !isset($_GET['PRODJS']) ? "dev" : "prod",
	'type' 	=> in_array(_APP_CONTEXT, array('local','dev')),
);

// 
error_reporting(E_ERROR | E_PARSE);							// Report simple running errors


# Security
ini_set('register_globals', 0); 							//
ini_set('magic_quotes_gpc', 0); 							//
ini_set('magic_quotes_runtime', 0); 						//
ini_set('magic_quotes_sybase', 0); 							//


# Dev/Debug
ini_set('xdebug.var_display_max_children', 1024); 			//
ini_set('xdebug.var_display_max_data', 99999); 				//
ini_set('xdebug.var_display_max_depth', 6); 				//
ini_set('xdebug.max_nesting_level', 500); 					// default is 100, which can be cumbersome


// Force timezone to UTC
//$old = date_default_timezone_get();
// TODO: handle this properly (allow user to choose its tz)
date_default_timezone_set('UTC');


?>