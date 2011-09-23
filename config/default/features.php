<?php 

define("_IN_MAINTENANCE", 						false); 					// Set this to true to redirect all requests to the maintenance page (/maintenance)
	
### VERSIONING	
define('_PHPGASUS_VERSION', 					'1.0.0.0a');	
define("_APP_VERSION", 							'1.0.0.0');	
define("_ASSETS_MAIN_VERSION", 					'170820111451'); 			// 
define("_JS_VERSION", 							_ASSETS_MAIN_VERSION); 		//
define("_CSS_VERSION", 							_ASSETS_MAIN_VERSION); 		//
define("_FLASH_VERSION", 						_ASSETS_MAIN_VERSION); 		//


### APP METADATA	
define('_APP_NAMESPACE', 						'pgas');	
define('_APP_TITLE', 							_APP_NAME); 				// App displayed name
define('_APP_BASELINE', 						'');	
define('_APP_AUTHOR_NAME', 						_APP_NAME);	
define('_APP_AUTHOR_MAIL', 						'contact@' . _DOMAIN);	
define('_APP_AUTHOR_URL', 						_URL);	
define('_APP_OWNER_NAME', 						_APP_NAME);	
define('_APP_OWNER_MAIL', 						'contact@' . _DOMAIN);	
define('_APP_OWNER_CONTACT_MAIL', 				'contact@' . _DOMAIN);	
define('_APP_OWNER_URL', 						_URL);	


### TEMPLATES ###	
define('_TEMPLATES_ENGINE', 					'Smarty'); 					// Smarty, Twig or Haanga (case sensitive)
define('_TEMPLATES_EXTENSION', 					'tpl'); 					//
//define('_TEMPLATES_ENGINE', 					'Twig'); 					// Smarty, Twig or Haanga (case sensitive)
//define('_TEMPLATES_EXTENSION', 				'twig.tpl'); 				//
define("_TEMPLATES_COMPILE_CHECK", 				false);
define("_TEMPLATES_FORCE_COMPILE", 				false);
define("_TEMPLATES_CACHING", 					false);
define("_TEMPLATES_CACHE_LIFETIME", 			3600); // in seconds


### ACCOUNT & SESSIONS HANDLING ###	
define('_SESSION_NAME', 						'token'); 					// Name of the sessions
define('_APP_ALLOW_GET_SID_FROM_URL', 			false); 					// For security issues, it's recommanded not to allow passing session id in URLs, unless you use https and/or are sure of what you do 
define('_APP_USE_ACCOUNTS', 					true); 						// Disable this prevent app from trying to update sessions table on each page load
define('_APP_ALLOW_SIGNUP', 					false); 					// Allow users to sign up by themselves
define('_APP_SESSION_DURATION', 				900); 						// In seconds. (ex: 900s = 15 minutes)
define('_APP_IS_SESSION_CROSS_SUBDOMAIN', 		true); 						// 
define('_APP_KEEP_OLD_SESSIONS', 				false); 					// By default, when a user login, its sessions older than 1 day are deleted
define('_APP_USE_ACCOUNTS_CONFIRMATION', 		true);          			// Will require accounts to be confirmed (email sent with activation link)
define('_APP_MAX_LOGIN_ATTEMPTS', 				5);          				// If the user tries to login more than X times, it's account will be blocked for some time (0 = no limit)
define('_APP_MAX_LOGIN_ATTEMPTS_BAN_TIME', 		7200);          			// Duration (in seconds) of the ban of the account due to too many login attemps
define('_APP_ALLOW_LOST_PASSWORD_RESET', 		true); 						// Allow users to reset their's password (send a mail with a link to reset it)
define('_APP_IP_WHITELIST', 					''); 						// CSV list of IP adresses than could not be banned. Ex: 127.0.0.1,192.168.0.1


### DEV / DEBUG ###		
define('_USE_FIREPHP_LOGGING',      			true); 						// experimental. In local & dev environment, use FirePHP server lib to log data (using $this->dump()) into Firefox console (require related extension).
define('_USE_CHROMEPHP_LOGGING',      			true); 						// experimental. In local & dev environment, use ChromePHP server lib to log data (using $this->dump()) into Chrome console (require related extension).		
define('_USE_EVENTS',                       	true);          			// Disable this if you do not need to use events 
define('_APP_USE_SQL_TYPEFIXING',               false);         			// experimental.
define('_APP_USE_ONFETCH_TYPEFIXING', 			true);          			// experimental.
define('_APP_TYPEFIX_ONETOONE_GETFIELDS',       true);          			// experimental.
define('_APP_TYPEFIX_MANYTOMANY_GETFIELDS',     true);          			// experimental.
define('_APP_USE_DEFERED_JS',                   false);         			// experimental.
define('_XML2ARRAY_FIX_TEXT_NODES_ATTRIBUTES',  true);          			// experimental.
define('_APP_ENABLE_SPLITED_ONE2ONE_COLS',      true); 						// experimental.
define('_APP_FETCH_RELATED_ONETOMANY', 			false); 					// experimental. Automatically fetch related onetomany items


### ADMIN ###	
define('_APP_LIMIT_RETRIEVED_RESOURCES', 		100); 						// Max number of rows/items that can be retrieved (by default) by a query (for perf issues). Use an explicit 'limit' param if you want more 
define('_ADMIN_RESOURCES_NB_PER_PAGE', 			100); 						// deprecated. use _APP_LIMIT_RETRIEVED_RESOURCES
define('_APP_SEARCH_ALWAYS_GLOBAL',             true); 						// experimental.
define('_APP_USE_RESOURCESGROUPS',              true);          			// experimental. 


### MISC SNIFFING, FEATURES DETECTION ###		
define('_SNIFF_PLATFORM', 						true); 						// Disable this if you don't want to try getting the platform data (prevent unnecessary processing)
define('_SNIFF_BROWSER', 						true); 						// Disable this if you don't want to try getting the browser data (prevent unnecessary processing)
define('_USE_MODERNIZR', 						true); 						// If allowed, the js lib Modernizr will be added to detect user browser capabilities adding subsenquent classes to the <HTML> tag



### LANGUAGES & INTERNATIONALISATION ###
define('_I18N_SYSTEM', 							'db'); 						// 'db' (database + gettext), 'po' (.po files + gettext), 'php'
define('_DEFAULT_LANGUAGE', 					'fr_FR'); 					// or en_US, en_GB, de_DE, es_EN, it_IT, ja_JP, zh_CN, ko_KR
define('LANGUAGES', 							'fr_FR'); 					// List of languages availables for the app, separated by comas



### HTML & METAS ###			
define('_APP_DOCTYPE', 							'html5');					// 'html5', 'xhtml-strict-1.1', 'xhtml-strict', 'xhtml-transitional', 
define('_APP_DEFAULT_OUTPUT_FORMAT', 			'html');					// Is there case where it won't be html?
define('_APP_DEFAULT_OUTPUT_MIME', 				'text/html');				// 
define('_APP_USE_MANIFEST', 					false);						//
define('_APP_MANIFEST_FILENAME', 				_APP_TITLE . '.manifest'); 	// 
define('_APP_META_DECRIPTION', 					'');
define('_APP_META_KEYWORDS', 					'');
define('_APP_META_ROBOTS_INDEXABLE', 			false);						// Allows/prevents pages to be indexed by Google & Friends?
define('_APP_META_ROBOTS_ARCHIVABLE', 			false);						// Allows/prevents search engines to display "in cache" links in their search results
define('_APP_META_ROBOTS_IMAGES_INDEXABLE', 	false);						// Allows/prevents search engines to index your images
define('_APP_META_GOOGLE_TRANSLATABLE', 		true);						// Allows/prevents Google to offer translation link/feature for your pages
define('_APP_ALLOW_PAGE_PRERENDERING', 			false);						// Allows/prevents Google Chrome to prepender pages in background 
define('_USE_CHROME_FRAME', 					true);						// HTML pages require Google Chrome Frame plugin? (if yes, displays plugin installation popup)
define('_USE_BROWSER_UPDATE_NOTIFIER', 			true); 						// Will display a notification to users whose browsers is not up to date


### VIEWPORT & WEBAPP HANDLING ###		
define('_APP_VIEWPORT_WIDTH', 					'device-width');			// Viewport width
define('_APP_VIEWPORT_INI_SCALE', 				1.0); 						// Initial scale of the viewport
define('_APP_VIEWPORT_MAX_SCALE', 				3.0); 						// Maximum scale of the viewport
define('_APP_VIEWPORT_USER_SCALABLE', 			true); 						// Allow user to resize the viewport
define('_APP_IOS_WEBAPP_CAPABLE', 				false); 					// 
// TODO: deprecate?		
define('_APP_IOS_INISCALE', 					'1.0'); 					// Default page scale for iphones (default = 1.0)
define('_APP_IOS_MAXSCALE', 					'3.0'); 					// Allow iphones to scale up/down pages (default = 1.0) 
define('_APP_IPHONE_MAXSCALE', 					'3.0'); 					// Allow iphones to scale up/down pages (default = 1.0)
define('_APP_IPAD_INISCALE', 					'1.0'); 					// Default page scale for ipads (default = 1.0)
define('_APP_IPAD_MAXSCALE', 					'3.0'); 					// Allow ipads to scale up/down pages (default = 1.0)


### CSS/JS & MISC PERF CONFS ###
define('_APP_USE_CSS_IE', 						true); 						// Enable/disable loading of specific css for Internet Explorer
define("_ALLOWED_CSS_EXT_PATTERN", 				'(s|c|sc|le)ss'); 			// regex pattenr of allowed css file extensions. (=== 'css|scss|sass|less')
define("_ALLOWED_JS_EXT_PATTERN", 				'js|coffee'); 				// regex pattenr of allowed css file extensions. (=== 'css|scss|sass|less')
define("_MINIFY_JS", 							true); 						// Enable/disable automatic minification for js
define("_MINIFY_CSS", 							true); 						// Enable/disable automatic minification for css
define("_MINIFY_HTML", 							true); 						// TODO: Enable/disable automatic minification for html
define("_FLUSH_BUFFER_EARLY", 					true); 						// Experimental. Enable/disable flush the buffer at some stategic parts of the templates

	
### THIRD PARTY SERVICES ###	
	
# Google Analytics #
define('_APP_USE_GOOGLE_ANALYTICS', 			false); 					//
define('_APP_GOOGLE_ANALYTICS_UA', 				'UA-XXXXX-X'); 				//
		
# Google APIs (http://www.google.com/apis/maps/signup.html) #
define('_APP_GOOGLE_MAPS_API_KEY',               ''); 						//
		
# Amazon webservices #
define('_AWS_ACCESSKEY', 						'F4k3k3y'); 				//
define('_AWS_SECRET_KEY', 						'F4k3S3cr3tK3y');			//
define('_AWS_BASE_BUCKET', 						'F4k3Buck3etN4me'); 		//

# Apple Push Notifications #
define('_APP_IPHONE_PUSH_GATEWAY_TEST', 		'ssl://gateway.sandbox.push.apple.com:2195');
define('_APP_IPHONE_PUSH_GATEWAY_PROD', 		'ssl://gateway.push.apple.com:2195');


?>