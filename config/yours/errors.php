<?php 

// Load default conf params (conf defined above will be ignored)
include( _PATH_CONFIG . 'default/errors.php' );

//
// You can also define the css this localy in your controller (using $this->view->css)
$_errors = array_merge($errors, array(
	// insert your errors here if you want
));

?>