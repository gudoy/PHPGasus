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
if ( _IN_MAINTENANCE ) 						{ $directCall = true; $RC->name = 'CHome'; $RC->rawName = 'home'; $RC->method = 'maintenance'; $RC->calledMethod = 'maintenance'; }

// Special case for home
// Since we know it's home, we can avoid some overhead by directly calling the controller
// Which is good to have a fast response for the propably most visited page of your site
//elseif ( $_SERVER['REDIRECT_URL'] === '/' ) { $directCall = true; $RC->name = 'CHome'; $RC->method = 'index'; }
elseif ( str_replace(rtrim(_PATH_REL, '/'), '', $_SERVER['REDIRECT_URL']) === '/' ) { $directCall = true; $RC->name = 'CHome'; $RC->rawName = 'home'; $RC->method = 'index'; $RC->calledMethod = 'index'; }

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

//var_dump($item);
//var_dump($RC);
	
	// Is an existing folder in controllers
	if ( ( $isDir = is_dir($cPath . $item) ) && $isDir )
	{
//var_dump('is_dir: ' . $item);
//var_dump('has next: ' . $hasNext);

//var_dump($cPath . $item . '/' . $cName . '.class.php');
		
		if ( ( $isFile = file_exists($cPath . $item . '/' . $cName . '.class.php') ) && $isFile )
		{
//var_dump('is file: ' . $cPath . $item . '/' . $cName . '.class.php');
			$RC->name = 'C' . ucfirst($item);
			$RC->path = $cPath . $item . '/';
		}

		if ( $hasNext ){ $Request->breadcrumbs[] = $item; continue; }
	}
	elseif ( ( $isFile = is_file($cFilepath) ) && $isFile )
	{
		$RC->name = 'C' . ucfirst($item);
	}
	
//var_dump($RC);
	
	// Load controller
	class_exists($RC->name) || require($RC->path . $RC->name . '.class.php');
	
	// Get method & params to dispatch
	$RC->rawName 	= strtolower(substr($RC->name, 1));
	//$RC->method 	= $isDir && $isFile ? null : $item;
	$RC->method 	= $isDir || $isFile ? null : $item;
	$RC->params 	= $hasNext ? array_slice($Request->parts, $i+1) : array();
	
	// Allways call dispatchMethod (that will redispatch to proper method)	
	return call_user_func(array(new $RC->name($Request), 'dispatchMethod'));
}

?>