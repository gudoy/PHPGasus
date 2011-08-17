<?php

$libs       = 'default/libs/'; 		// Shortcurt for default libs folder
$pages      = 'default/pages/'; 	// Shortcurt for default pages folder
$ylibs      = 'yours/libs/'; 		// Shortcurt for your libs folder
$ypages     = 'yours/pages/'; 		// Shortcurt for your pages folder

$_js = array(
	# Libs
	//'jquery' 				=> array($libs . 'jquery-1.6.2.min.js'),
	'jquery' 				=> array('https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js'),
	
	'jqueryUI'				=> array($libs . 'jquery-ui-1.8.15.custom.min.js'),
	'jqueryPlusUI' 			=> array('jquery', 'jqueryUI'),
	'jqueryEasing' 			=> array($libs . 'jquery.easing.1.3.js'),
	'timepicker' 			=> array($libs . 'jquery-ui-timepicker-addon.js'),
	'modernizr' 			=> _APP_USE_MODERNIZR ? array($libs . 'modernizr.custom.js') : array(),
	'tools' 				=> array('default/tools.js'),
	
	# PHPGasus defaults
	'common' 				=> array('jqueryPlusUI', 'modernizr', 'tools', 'default/app.js'),
	'admin' 				=> array('default', 'timepicker', $pages . 'admin.js'),
	//'adminHome' 			=> array('admin', $pages . 'adminSpecifics.js'),
	//'adminResourcesCreate' 	=> array('default', $pages . 'admin/adminResources.js'),
	//'adminResourcesUpdate' 	=> array('default', $pages . 'admin/adminResources.js'),
	//'accountLogin' 			=> array('default', $libs . 'jquery.cookie.js', $pages . 'account/login.js'),
	//'apiHome' 				=> array('default', $pages . 'api/home.js'),
	
	# App specifics
	//'adminSpecifics' 	=> array('controllers/admin/adminCommon.js','controllers/admin/adminSpecifics.js',),
);

?>