<?php

// Load default css
include( _PATH_CONFIG . 'default/css.php' );

// Insert your css groups below
$_css = array_merge($_css, array(
	// You can also define the css localy in your controller method (using $this->view->css)
	// or disable css for a page setting $this->view->css = false in your controller method
	// You can use '--myFile.css' or '--myGroup' to remove them from the final files array
));

?>