<?php

$t1 = microtime(true);
$m1 = memory_get_usage();

// Load config file(s)
require 'config/config.generated.php';

$Request 	= new Request(); 			// Create a new request
$RC 		= &$Request->controller; 	// Shortcut to request controller

//var_dump($Request);

// If the site is in maintenance
if ( _IN_MAINTENANCE ) 						{ $direcCall = true; $RC->name = 'CHome'; $RC->method = 'maintenance'; }
// Special case for home
elseif ( $_SERVER['REDIRECT_URL'] === '/' ) { $direcCall = true; $RC->name = 'CHome'; $RC->method = 'index'; }

if ( $directCall ) { return call_user_func(array(new $RC->name($Request), $RC->method)); }

$RC->name 	= 'CHome';
$RC->path 	= _PATH_CONTROLLERS;

// Otherwise,
// loop over the request parts
$i = -1;
foreach ($Request->parts as $item)
{
	$i++;	
	$item 		= strtolower($item); 															// Lowercase the item
	$hasNext 	= isset($Request->parts[$i+1]); 												// Check if there's a next part to check against
	$cName 		= 'C' . ucfirst($item); 														// Controller name
	$cPath 		= _PATH_CONTROLLERS . 
					( $Request->breadcrumbs ? join('/', $Request->breadcrumbs) . '/' : '' ); 	// Controller path
	$cFilepath 	= $cPath . $cName . '.class.php'; 												// Controller file path	

//var_dump($item);
//var_dump($cFilepath);
	
	// Is an existing folder in controllers
	if ( ( $isDir = is_dir($cPath . $item) ) && $isDir )
	{
//var_dump('is dir: ' . $item);
//var_dump('next: ' . $Request->parts[$i+1]);

		if ( ( $isFile = file_exists($cPath . $item . '/' . $cName . '.class.php') ) && $isFile )
		{
			$RC->name = 'C' . ucfirst($item);
			$RC->path = $cPath . $item . '/';
		}

		if ( $hasNext ){ $Request->breadcrumbs[] = $item; continue; }
	}

//var_dump('should call');
//var_dump($item);
//var_dump($Request->parts);
//var_dump($Request->params);
		
	class_exists($RC->name) || require($RC->path . $RC->name . '.class.php');
	
	// Get method & params to dispatch
	$RC->rawName 	= strtolower(substr($RC->name, 1));
	$RC->method 	= $isDir && $isFile ? null : $item;
	$RC->params 	= $hasNext ? array_slice($Request->parts, $i+1) : array();
	
//var_dump($RC);
//var_dump($Request->breadcrumbs);
	
	// Allways call dispatchMethod (that will redispatch to proper method)	
	return call_user_func(array(new $RC->name($Request), 'dispatchMethod'));
}

?>