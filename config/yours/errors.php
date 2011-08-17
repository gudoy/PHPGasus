<?php 

// Load default conf params (conf defined above will be ignored)
include( _PATH_CONFIG . 'default/errors.php' );

//
$_errors = array_merge($errors, array(
	// insert your errors here if you want
));

?>