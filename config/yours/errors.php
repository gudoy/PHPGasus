<?php 

// Report simple running errors
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

// Load default conf params (conf defined above will be ignored)
include( _PATH_CONFIG . 'default/errors.php' );

//
$_errors = array_merge($errors, array(
	// insert your errors here if you want
));

?>