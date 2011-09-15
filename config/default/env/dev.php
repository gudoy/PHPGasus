<?php

#########
## DEV ##
#########

define("_TEMPLATES_COMPILE_CHECK", 				true);
define("_TEMPLATES_FORCE_COMPILE", 				false);
define("_TEMPLATES_CACHING", 					false); 

define("_MINIFY_JS",							false);
define("_MINIFY_CSS", 							false);

define("_DB_USER",                  			'F4K3us3r');
define("_DB_PASSWORD",  						'F4K3paSSw0rD');

define("_APP_USE_GOOGLE_ANALYTICS",  			false); // Disable Google Analytics

# Errors handling
//error_reporting(2147483647); 			// === error_reporting(E_ALL | E_STRICT | E_DEPRECATED)
error_reporting((E_ALL | E_STRICT | E_DEPRECATED) ^ E_NOTICE);
ini_set('display_errors', 1);
ini_set('html_errors', 1);

?>