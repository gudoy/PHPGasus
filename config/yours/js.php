<?php

// Load default js
include( _PATH_CONFIG . 'default/js.php' );

// Insert your js groups below
$_js = array_merge($_js, array(
	// You can also define the js localy in your controller method (using $this->view->js)
	// or disable js for a page setting $this->view->js = false in your controller method
	// You can use '--myFile.js' or '--myGroup' to remove them from the final files array
));

?>