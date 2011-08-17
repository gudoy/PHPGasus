<?php 

# Base
define("_URL_HOME", 						_URL);
define("_URL_PUBLIC", 	 					_URL . 'public/');
define("_URL_STYLESHEETS",					_URL_PUBLIC . 'stylesheets/default/');
define("_URL_JAVASCRIPTS", 	 				_URL_PUBLIC . 'javascripts/');
define("_URL_CSS",							_URL_STYLESHEETS);
define("_URL_JS", 	 						_URL_JAVASCRIPTS);
define("_URL_MEDIA", 	 	 				_URL_PUBLIC . 'medias/');
define("_URL_FLASHS", 	 	 				_URL_MEDIAS . 'flash/');
define("_URL_AUDIOS", 	 	 				_URL_MEDIAS . 'audios/');
define("_URL_VIDEOS", 	 	 				_URL_MEDIAS . 'videos/');
//define("_URL_IMAGES", 						_URL_STATIC_1 . 'medias/images/');
define("_URL_IMAGES", 						_URL_MEDIAS . 'images/');

# Base (relative)
define("_URL_PUBLIC_REL", 	 				_URL_REL . 'public/');
define("_URL_STYLESHEETS_REL",				_URL_PUBLIC_REL . 'stylesheets/default/');
define("_URL_JAVASCRIPTS_REL", 	 			_URL_PUBLIC_REL . 'javascripts/');
define("_URL_CSS_REL",						_URL_STYLESHEETS_REL);
define("_URL_JS_REL", 	 					_URL_JAVASCRIPTS_REL);

# Common
define("_URL_DOWN", 						_URL . 'down/');
define("_URL_MAINTENANCE", 					_URL . 'maintenance/');
define("_URL_404", 							_URL . 'error404/');

# Search
define("_URL_SEARCH", 						_URL . 'search/');
define("_URL_SEARCH_ADVANCED", 				_URL . 'search/advanced/');

# About
define("_URL_ABOUT", 						_URL . 'about/');
define("_URL_SITEMAP", 						_URL . 'about/sitemap/');
define("_URL_ABOUT_TU", 					_URL . 'about/termsofuse/');
define("_URL_ABOUT_TCS", 					_URL . 'about/termsofsale/');
define("_URL_HELP", 						_URL . 'about/help/');
define("_URL_CONTACT", 						_URL . 'about/contact/');
define("_URL_ABOUT_CONTACT", 				_URL . 'about/contact/');

# Account
define("_URL_ACCOUNT", 						_URL . 'account/');
define("_URL_LOGIN", 						_URL . 'account/login/');
define("_URL_LOGOUT", 						_URL . 'account/logout/');
define("_URL_SIGNUP", 						_URL . 'account/signup/');
define("_URL_SIGN_SUCCESS", 				_URL . 'account/signup/success/');
define("_URL_ACCOUNT_CONFIRMATION", 		_URL . 'account/confirmation/');
define("_URL_ACCOUNT_PASSWORD_LOST", 		_URL . 'account/password/lost/');
define("_URL_ACCOUNT_PASSWORD_EDIT", 		_URL . 'account/password/new/');
define("_URL_ACCOUNT_PASSWORD_RESET", 		_URL . 'account/password/reset/');
define("_URL_RESEND_CONFIRMATION_MAIL", 	_URL . 'account/confirmation/');

# Admin
define("_URL_ADMIN", 						_URL . 'admin/');
define("_URL_ADMIN_DASHBOARD", 				_URL_ADMIN . 'dashboard/');
define("_URL_ADMIN_SEARCH",                 _URL_ADMIN . 'search/');
define("_URL_ADMIN_SETUP", 					_URL_ADMIN . 'setup/');
define("_URL_ADMIN_SETUP_RESOURCES", 		_URL_ADMIN_SETUP . 'resources/');

# API
define("_URL_API", 							_URL . 'api/');

?>