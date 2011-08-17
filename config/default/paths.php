<?php 

# Base
define("_PATH_CONTROLLERS",					_PATH . 'controllers/default/'); 		//  
define("_PATH_LIBS",						_PATH . 'libs/default/'); 				//  
//define("_PATH_LOGS",						_PATH . 'logs/'); 						//  
define("_PATH_TMP", 						_PATH . 'tmp/'); 						//  
define("_PATH_I18N", 						_PATH . 'i18n/'); 						//  
                                                                                       
# Static content                                                                       
define("_PATH_PUBLIC",						_PATH . 'public/'); 					//  
define("_PATH_IMAGES",						_PATH . 'public/media/images/'); 		//  
define("_PATH_STYLESHEETS",					_PATH . 'public/stylesheets/default/'); //  
define("_PATH_JAVASCRIPTS",					_PATH . 'public/javascripts/'); 		//  
                                                                                       
# Templates                                                                            
define("_PATH_TEMPLATES",					_PATH . 'templates/'); 					//  
define("_PATH_TEMPLATES_CACHE",				_PATH_TEMPLATES . '_cache/'); 			// 
define("_PATH_TEMPLATES_PRECOMPILED",		_PATH_TEMPLATES . '_precompiled/'); 	//
define("_PATH_SMARTY",						_PATH_LIBS . 'templating/Smarty/'); 	//
define("_PATH_TWIG",						_PATH_LIBS . 'templating/Twig/'); 		//
define("_PATH_HAANGA",						_PATH_LIBS . 'templating/Haanga/'); 	//

                                                                                       
# Aliases (for convenience)                                                                               
define("_PATH_CONF",						_PATH_CONFIG); 							// 
define("_PATH_TPL",							_PATH_TEMPLATES); 						// 
define("_PATH_JS",							_PATH_JAVASCRIPTS); 					// 
define("_PATH_CSS",							_PATH_STYLESHEETS); 					// 

?>