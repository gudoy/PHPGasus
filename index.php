<?php

// Get start time & used memory (for later use)
$_phpgasus = array(
	'tstart' => microtime(true),
	'mstart' => memory_get_usage(),
);

// Load config file(s)
require 'config/config.generated.php';

$Request 	= new Request(); 			// Create a new request
$RC 		= &$Request->controller; 	// Shortcut to request controller

// If the site is in maintenance
if ( _IN_MAINTENANCE ) 						{ $direcCall = true; $RC->name = 'CHome'; $RC->method = 'maintenance'; }

// Special case for home
// Since we know it's home, we can avoid some overhead by directly calling the controller
// Which is good to have a fast response for the propably most visited page of your site
//elseif ( $_SERVER['REDIRECT_URL'] === '/' ) { $direcCall = true; $RC->name = 'CHome'; $RC->method = 'index'; }
elseif ( str_replace(rtrim(_PATH_REL, '/'), '', $_SERVER['REDIRECT_URL']) === '/' ) { $direcCall = true; $RC->name = 'CHome'; $RC->method = 'index'; }

if ( $directCall ) { return call_user_func(array(new $RC->name($Request), $RC->method)); }

$RC->name 	= 'CHome';
$RC->path 	= _PATH_CONTROLLERS;

// Otherwise,
// Loop over the request parts
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
	
	// Is an existing folder in controllers
	if ( ( $isDir = is_dir($cPath . $item) ) && $isDir )
	{
		if ( ( $isFile = file_exists($cPath . $item . '/' . $cName . '.class.php') ) && $isFile )
		{
			$RC->name = 'C' . ucfirst($item);
			$RC->path = $cPath . $item . '/';
		}

		if ( $hasNext ){ $Request->breadcrumbs[] = $item; continue; }
	}
	
	// Load controller
	class_exists($RC->name) || require($RC->path . $RC->name . '.class.php');
	
	// Get method & params to dispatch
	$RC->rawName 	= strtolower(substr($RC->name, 1));
	$RC->method 	= $isDir && $isFile ? null : $item;
	$RC->params 	= $hasNext ? array_slice($Request->parts, $i+1) : array();
	
	// Allways call dispatchMethod (that will redispatch to proper method)	
	return call_user_func(array(new $RC->name($Request), 'dispatchMethod'));
}

?>