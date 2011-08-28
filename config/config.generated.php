<?php

// TODO: replace includes by properly generated config file

// 
define("_PATH_CONFIG", 					dirname(__FILE__) . '/'); 
define("_APP_CONTEXT", 					!getenv("APP_CONTEXT") ? 'prod' : getenv("APP_CONTEXT"));

$confdir = _PATH_CONFIG . 'yours/';

include( $confdir . 'env/' . _APP_CONTEXT . '.php' );
include( $confdir . 'env/_common.php' );
include( $confdir . 'paths.php' );
include( $confdir . 'features.php' );
include( $confdir . 'urls.php' );
?>