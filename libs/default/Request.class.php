<?php

class Request
{
	static $_instance;
	
	public $url;
	//static public $isAjax;
	//public static $url;
	public static $isAjax;
	
	public $outputFormat;
	
	public $params 			= array();
	public $breadcrumbs 	= array();
	
	public $knownExtensions = array('html','xhtml','json','jsonp','xml','plist','yaml','csv','qr','plistxml','yamltxt','jsontxt','jsonreport');
	public $knownMimes 		= array(
		'text/html' 				=> 'html',
		'application/xhtml+xml' 	=> 'xhtml',
		'application/json' 			=> 'json',
		'text/json' 				=> 'json',
		'application/json-p' 		=> 'jsonp',
		//'application/json' 			=> 'jsonp',
		//'application/javascript' 	=> 'jsonp',
		'text/xml' 					=> 'xml', 
		'application/xml' 			=> 'xml',
		'application/plist+xml' 	=> 'plist',
		'text/yaml' 				=> 'yaml',
		'text/csv' 					=> 'csv',
		// TODO: RSS
		// TODO: ATOM
		// TODO: RDF
		// TODO: ZIP
	);
	
	public function __construct()
	{		
		// In case where the app do not use a hostname but is accessed instead via an IP, we are to remove the app base base from the request URI
		$this->relative_uri = str_replace(rtrim(_PATH_REL, '/'), '', $_SERVER['REQUEST_URI']);
		
		// TODO: bench preg_split + replaced request uri, preg_split + redirect_url, explode + skiping '/' in dispatch
		//$this->parts 		= preg_split('/\//', trim(str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']), '/'));
		$this->parts 		= preg_split('/\//', trim(str_replace('?' . $_SERVER['QUERY_STRING'], '', $this->relative_uri), '/'));
		//$this->parts = preg_split('/\//', trim($_SERVER['REDIRECT_URL'], '/'));
		
		// Init request data (filters, condtions, columns, values)
		$this->filters 		= array();
		$this->columns 		= array();
		$this->conditions 	= array();
		$this->values 		= array();
		$this->restrictions = array();
		
		$this->getURL();
		
		//$this->extension 	= strpos($uriParts['path'], '.') !== false ? preg_replace('/(.*)\.(.*)/', '$2', $uriParts['path']) : null;
		$this->extension 	= strrpos($_SERVER['REDIRECT_URL'], '.') !== false ? substr($_SERVER['REDIRECT_URL'], strrpos($_SERVER['REDIRECT_URL'], '.')+1 ) : null;
		$ext = preg_split('/\./', basename($_SERVER['REDIRECT_URL']));
		
		$this->controller = (object) array();
		//$this->controller = new ArrayObject(array(), 2);
		
		$this->sniffPlatformData();
        $this->sniffDeviceData();
        $this->sniffBrowserData();
		
		$this->getOutputFormat();
	}
	
	public function setLanguage($lang = null)
	{
		// Get known languages and force them into lowercase
		$known 		= defined('_APP_LANGUAGES') && is_array(_APP_LANGUAGES) 
						? explode(',', strtolower(join(',', _APP_LANGUAGES))) 
						: explode(',', strtolower(_APP_LANGUAGES));
		
		// Get  Accept-Language http header 
		// ex: fr,fr-fr;q=0.8,en-us;q=0.5,en;q=0.3
		$accptHeader = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? str_replace('-', '_', $_SERVER['HTTP_ACCEPT_LANGUAGE']) : '';
		
		// Try to find lang in GET param
		if ( empty($lang) && isset($_GET['lang']) && in_array(strtolower($_GET['lang']), $known) )			{ $lang = strtolower($_GET['lang']); }
		
		// Try to find lang in POST param
		if ( empty($lang) && isset($_POST['lang']) && in_array(strtolower($_POST['lang']), $known) )		{ $lang = strtolower($_POST['lang']); }
		
		// Try to find lang in SESSION param
		if ( empty($lang) && isset($_SESSION['lang']) && in_array(strtolower($_SESSION['lang']), $known) )	{ $lang = strtolower($_SESSION['lang']); }
		
		// If the lang has not been found and if there's an Accept-language http header
		if ( empty($lang) && !empty($accptHeader) )
		{
			$acptLangs 	= array(); 						//  
			$lgs 		= explode(',', $accptHeader); 	// Split it
			
			// Loop over them and build an array of the form (lang => priority)
			foreach ( (array) $lgs as $lg )
			{
				$pos 				= strpos($lg, ';q=');
				$key 				= $pos !== false ? substr($lg, 0, $pos) : $lg;
				$acptLangs[$key] 	= $pos ? substr($lg, $pos + 3) : 1;
			}
			
			// Sort array by value (priority)
			arsort($acptLangs);
			
			// Check for match between accepted languages and known ones
			foreach ($acptLangs as $lg => $priority){ if ( in_array(strtolower($lg), $known) ){ $lang = $lg; break; } }
		}
		
		// If the lang has still not been found, use the default language
		if ( empty($lang) && defined('_APP_DEFAULT_LANGUAGE') ) { $lang = strtolower(_APP_DEFAULT_LANGUAGE); }

		$parts 		= strpos($lang, '_') !== false ? explode('_', $lang) : array($lang);  
		$language 	= $parts[0];
		$territory 	= strtoupper( !empty($parts[1]) ? $parts[1] : $parts[0] );
		$codeset 	= 'UTF-8';
		$locale 	= $language . '_' . $territory . '.' . $codeset;
		
		// Set locale & gettext conf
		putenv('LANG=' . $locale);
		putenv('LC_ALL=' . $language . '_' . $territory);
		$lc = setlocale(LC_ALL, $locale, $language . '_' . $territory, $language);
		bindtextdomain(_APP_NAME, _PATH_I18N);
		textdomain(_APP_NAME);
		bind_textdomain_codeset(_APP_NAME, $codeset);
		
		// Store the current lang
		$_SESSION['lang'] 	= $language . '_' . $territory;
	}

	public function getMagicData()
	{
		// Set default values
		$this->_magic = array(
			'objects' 	=> array(),
			'name' 		=> null,
			'classes' 	=> array(),
			//'css' 		=> null,
			'jsCalls' 	=> null,
			'cacheId' 	=> null,
		);
		
		// Set some shortcuts
		$_rq 			= &$this->request; 				// Request
		$_c 			= &$this->controller; 			// Controller
		$_mg 			= &$this->_magic; 				// View magic data

		$camel 			= '';
		$pointed 		= '';
		$jsObject 		= "if ( typeof object !== 'undefined' ){ --next-- }";
		$jsFunction 	= "if ( typeof object.init === 'function' ){ object.init(); --next-- }";
		$jsFunction2 	= "if ( typeof object.item === 'function' ){ object.item(); }";
		
		$_mg['jsCalls'] .= PHP_EOL . $jsObject;
		
		$items 			= $this->breadcrumbs;
		array_push($items, $_c->rawName, $_c->calledMethod);		
		$i 				= 0;
		$lim 			= count($items);
		$curObj 		= '';
		$curTabs 		= '';
		
		foreach ( $items as $item )
		{
			// Get current camelcased concatenation of all parts
			$camel 				.= !empty($camel) ? ucfirst($item) : $item;
			
			// Set the current concatenation as a magic object
			$_mg['objects'][] 	= $camel;
			
			// Set the current item as a magic class (except for the last item)
			if ( $i < $lim - 1 ){ $_mg['classes'][] = $item; }   					
			
			// first item (object)
			if ( $i === 0 )
			{
				$curObj 	.= !empty($curObj) ? '.' . $item : $item; 		// Get current pointed notation concatenation of all parts
				$curTabs 	.= "\t";
				$_mg['jsCalls'] = str_replace('object', $curObj, $_mg['jsCalls']);
				$tmp 			= PHP_EOL . $curTabs . str_replace('object', $curObj, $jsFunction);
				$_mg['jsCalls'] = str_replace('--next--', $tmp, $_mg['jsCalls']);
			}
			// not first, not last (object)
			elseif ( $i < $lim - 1 )
			{
				$curObj 			.= !empty($curObj) ? '.' . $item : $item; 		// Get current pointed notation concatenation of all parts
				$tmp 			= PHP_EOL . $curTabs . str_replace('object', $curObj, $jsObject);
				$_mg['jsCalls'] = str_replace('--next--', $tmp, $_mg['jsCalls']);
				$curTabs 	.= "\t";
				$tmp 			= PHP_EOL . $curTabs . str_replace('object', $curObj, $jsFunction);
				$_mg['jsCalls'] = str_replace('--next--', $tmp, $_mg['jsCalls']);
			}
			// last item (function)
			else
			{
				$tmp 			= PHP_EOL . $curTabs . str_replace(array('object', 'item'), array($curObj, $item), $jsFunction2) . PHP_EOL;
				$_mg['jsCalls'] = str_replace('--next--', $tmp, $_mg['jsCalls']);
			}
			
			$i++;
		}

		return $_mg;
	}

	public function sniffPlatformData()
	{
		// Default values
		$this->platform = new ArrayObject(array(
			'name' 		=> 'unknownPlatform',
			'version' 	=> 'unknownVersion',
		), 2);
		
		// Do not continue if platform sniffing has been disabled
		if ( !_SNIFF_PLATFORM ) { return $this; }
		
		// Shortcut for user agent
		$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		
		// List of known platforms
		$platforms = array(
			'Windows','Mac OS','linux','freebsd', 							// OS
			'iPhone','iPod','iPad','Android','BlackBerry','Bada','mobile', 	// Mobile
			'hpwOS', 														// Tablets
			'j2me','AdobeAIR', 												// Specific
		);
		
		foreach ( $platforms as $p )
		{
			$lower 		= strtolower($p);
			$urlParam 	= 'is' . ucfirst($lower);
			
			// Check platform identifier is present in the user agent or is the is{$platform} parameter is set in the url
			if ( strpos($ua, $p) !== false || ( isset($_GET[$urlParam]) && ( $_GET[$urlParam] === '' || $_GET[$urlParam] != false) ) )
			{
				$this->platform['name'] = str_replace(' ', '', $lower);
				
				// Do not break since a platform can be build on top of another 
				//break;
			} 
		}
	}

    public function sniffDeviceData()
    {
        // Get resolution
        $resol          = !empty($_SESSION['resolution']) ? explode('x', strtolower($_SESSION['resolution'])) : array();
        $w              = !empty($resol[0]) ? (int) $resol[0] : null;
        $h              = !empty($resol[1]) ? (int) $resol[1] : null;
        
        // Default values
        $this->device  = new ArrayObject(array(
            'resolution'    => new ArrayObject(array('width' => $w, 'height' => $h), 2),
            'isMobile'      => isset($_GET['isMobile']) 
                                ? in_array($_GET['isMobile'], array('1', 'true',1,true))
                                : ( !empty($w) ? ($w < 800) : null ),
            'orientation' 	=> !empty($_SESSION['orientation']) 
                                ? $_SESSION['orientation'] 
                                : ( $w && $h ? ( $w > $h ? 'landscape' : 'portrait') : null),
        ), 2);
    }


	public function sniffBrowserData()
	{
		// Default values
		$this->browser 	= new ArrayObject(array(
			'engine' 		=> 'unknownEngine',
			'name' 			=> 'unknownBrowser',
			'version' 		=> new ArrayObject(array(
				'full' 			=> '?',
				'major' 		=> '?',
				'minor' 		=> '?',
				'build' 		=> '?',
				'revision' 		=> '?',
			), 2),
			//'hasHTML5' 			=> false,
		), 2);
		
		// Do not continue if browser sniffing has been disabled
		if ( !_SNIFF_BROWSER ) { return $this; }

		$ua 			= isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''; 	// Shortcut for user agent
		$_b 			= &$this->browser; 															// Shortcut for browser data
		
		// Known browsers data
		$engines 	= array(
			'Trident' 		=> 'trident', 
			'MSIE' 			=> 'trident', 
			'AppleWebKit' 	=> 'webkit', 
			'Presto' 		=> 'presto', 
			'Gecko' 		=> 'gecko', 
			'KHTML' 		=> 'khtml', 
			'BlackBerry' 	=> 'mango',
			'wOSBrowser' 	=> 'webkit',
		);
		$browsers 	= array(
			'MSIE' 			=> array('name' => 'internetexplorer', 'displayName' => 'Internet Explorer', 'alias' => 'ie', 'versionPattern' => '/.*(MSIE)\s([0-9]*\.[0-9]*);.*/'),
			'Firefox' 		=> array('alias' => 'ff', 'versionPattern' => '/.*(Firefox|MozillaDeveloperPreview)\/([0-9\.]*).*/'),
			'Chrome' 		=> array('versionPattern' => '/.*(Chrome)\/([0-9\.]*)\s.*/'),
			'Safari' 		=> array('versionPattern' => '/.*(Safari|Version)\/([0-9\.]*)\s.*/'),
			'Opera' 		=> array('versionPattern' => '/.*(Version|Opera)\/([0-9\.]*)\s?.*/'),
			'Konqueror' 	=> array('versionPattern' => '/.*(Konqueror)\/([0-9\.]*)\s.*/'),
            'BlackBerry' 	=> array('versionPattern' => '/.*(BlackBerry[a-zA-Z0-9]*)\/([0-9\.]*)\s.*/'),
		);
				
		// Try to get the browser data looking in the User Agent for knonw browser keys
		foreach ($browsers as $k => $b)
		{
			if (strpos($ua, $k) !== false)
			{
				$_b = new ArrayObject(array_merge((array) $_b, array(
					'name' 			=> !empty($b['name']) ? $b['name'] : strtolower($k),
					'id' 			=> $k,  
					'displayName' 	=> !empty($b['displayName']) ? $b['displayName'] : $k,
					'alias' 		=> !empty($b['alias']) ? $b['alias'] : strtolower($k),
				)), 2);
				break;
			}
		}
		
		// Try to get the browser rendering engine
		foreach ($engines as $k => $e) { if (strpos($ua, $k) !== false) { $_b['engine'] = $e; break; } }
		
		// Try to get the browser version data
		if ( $_b['id'] && ($pattern = $browsers[$_b['id']]['versionPattern']) && $pattern )
		{
			$p 				= explode('.', preg_replace($pattern, '$2', $ua)); 	// Split on '.'
			array_unshift($p, join('.', $p)); 									// Insert the full version as the 1st element
			$p 				= array_pad($p, count((array) $_b['version']), null); 		// Force the parts & default version arrays to have same length
			$_b['version'] 	= new ArrayObject(array_merge(
				(array) $_b['version'], 
				array_combine(array_keys((array) $_b['version']), $p)
			), 2); 																// Assoc default version array keys to found values
		}
	}

	public function getOutputFormat()
	{
		// Do not continue if the outputFormat is already defined
		if ( !empty($this->outputFormat) ){ return $this->outputFormat; }
        
		// Shortcut for params
		$p = &$this->params;
		
		// If no 'output' param has been passed or if the passed one is not part of the available formats
		//if ( empty($o['output']) && !in_array($o['output'], $this->knownMimes) )
		if ( empty($this->extension) || !in_array($this->extension, $this->knownExtensions) )
		{
			// Get the 'accept' http header and split it to get all the accepted mime type with their prefered priority
			$accepts 	= !empty($_SERVER['HTTP_ACCEPT']) ? explode(',',$_SERVER['HTTP_ACCEPT']) : array();
			
			$prefs 		= array();
			$i 			= 1;
			$len 		= count($accepts);
			foreach ($accepts as $item)
			{				
				$mime 			= preg_replace('/(.*);(.*)$/', '$1', trim($item)); 										// just get the mime type (or like)
				
				// Do not process mime types already that have already found earlier in the loop (prevent priority conflicts)
				if ( !empty($prefs[$mime]) ){ continue; }
				
				$q 				= strpos($item, 'q=') !== false ? preg_replace('/.*q=()(,;\s)?/Ui','$1',$item) : 1; 	// get the priority (default=1)
				$prefs[$mime] 	= $q*100 + ($len);
				$len--;
			}
			
			// Fix this fucking webkit that prefer xml over html
			if ( $this->browser['engine'] === 'webkit' )
			{				
				if ( isset($prefs['application/xml']) && isset($prefs['application/xhtml+xml']) && isset($prefs['text/html']) )
				{		
					$prefs['application/xml'] 	= $prefs['application/xml']-(2);
					$prefs['text/html'] 		= 150;
					
					if ( isset($prefs['image/png']) ){ $prefs['image/png'] = $prefs['application/xml']-(5); }
				}
			}
			
			/*
			if ( $this->browser['engine'] === 'webkit' && $this->platform['name'] === 'symbian' )
			{
				$prefs['text/html'] = 150;
			}*/
			
			// Fix this damn big fucking shit of ie that even does not insert text/html as a prefered type 
			// and prefers being served in their own proprietary formats (word,silverlight,...). MS screw you!!!!  
			if ( $this->browser['engine'] === 'trident' )
			{
				//if ( !isset($prefs['text/html']) ) { $prefs['text/html'] = 150; }
			}
			
			// Now, we add the default output format if not present
			//$def = _APP_DEFAULT_OUTPUT_MIME;
			//if ( !isset($prefs[$def]) ) { $prefs[$def] = 150; }
			
			// Sort by type priority
			arsort($prefs);
			
			// Now, loop over the types and break as soon as we found a recognized type
			foreach ($prefs as $pref => $priority)
			{ 
				// If it's a known type, stop here
				//if ( isset($this->knownMimes[$pref]) ){ $this->options['output'] = $this->knownMimes[$pref]; break; }
				if ( isset($this->knownMimes[$pref]) ){ $this->outputFormat = $this->knownMimes[$pref]; break; }
			}
		}

		// If nothing found, fallback to the default output format
		return isset($this->outputFormat) && in_array($this->outputFormat, $this->knownExtensions) 
				? $this->outputFormat
				: _APP_DEFAULT_OUTPUT_FORMAT;
	}


	public function handleParameters()
	{
		// TODO
		
		// Sanitize $_GET params
		
		// Check against resource + relatedResource 
	}
	
	static function isAjax()
	{
		$isAjax = isset(self::$isAjax) 
					? self::$isAjax 
					: ( 
						isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
						&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' 
						&& ( !isset($_GET['tplSelf']) || !in_array($_GET['tplSelf'], array('0','false')) ) 
					);
		
		self::$isAjax = $isAjax;
				
		return self::$isAjax; 
	}
	
	
	public function getClientIP()
	{
		// TODO
	}
	
	
	//public static function getURL()
	public function getURL()
	{
		if ( isset($this->url) ){ return $this->url(); }

		$this->url = self::url();
	}
	
	static function url()
	{
    	$protocol 		= _APP_PROTOCOL;
		$host 			= $_SERVER['SERVER_NAME'];
		$tmp 			= parse_url($protocol . $host . $_SERVER['REQUEST_URI']);
		$tmp['query'] 	= isset($tmp['query']) ? urlencode(urldecode($tmp['query'])) : '';
		$path 			= join('', $tmp);
		
		return $protocol . $host . $_SERVER['REQUEST_URI'];	
	}
	
	static function getParam(string $name)
	{
		return self::getURLParamsValue($this->url, $name);
	}
	
	static function getURLParamValue(string $url, string $param)
	{
		// Get start position of the param from the ?
		$markP 	= strpos($url, "?");
		$url 	= substr($url, $markP, strlen($url));
		$pos 	= strpos($url, $param);
		
		if ( $pos != -1 && !empty($param) )
		{
			// Truncate the string from this position to its end
			$tmp = substr($url, $pos);
			
			// Get end position of the param value
			if 		( strpos($tmp, "&amp;") !== false ) { $end_pos = strpos($tmp, "&amp;"); } 	// case where there are others params after, separated by a "&amp;"
			else if ( strpos($tmp, "&") !== false ) 	{ $end_pos = strpos($tmp, "&"); } 		// case where there are others params after, separated by a "&"
			else if ( strpos($tmp, "#") !== false ) 	{ $end_pos = strpos($tmp, "#"); } 		// case where there are others params after, separated by a "#"
			else 										{ $end_pos = strlen($tmp); } 			// case where there are no others params after
			
			// Truncate the string from 0 to the end of the param value
			return substr($tmp, strlen($param) + 1, $end_pos);
		}
		else { return false; }
	}

	static function getParams()
	{
		return self::getURLParams(self::$url);
	}

    static function getURLParams(string $url)
    {
        $params 	= array();
        $urlParts   = parse_url($url);
        $query      = !empty($urlParts['query']) ? $urlParts['query'] : '';
        
        foreach ( (array) explode('&', $query) as $item)
        {
            $parts              = explode('=', $item);
            $params[$parts[0]]  = !empty($parts[1]) ? $parts[1] : null; 
        }
        
        return $params;
    }
	
	
    static function removeQueryParams($paramNames, $string)
    {
        $cleaned = $string;
        
        foreach ((array)$paramNames as $paramName)
        {
            $cleaned = preg_replace('/(.*)[&]$/', '$1', preg_replace('/(.*)' . $paramName . '[=|%3D|%3d](.*)(&|$)/U','$1', $cleaned));
        }
        
        return $cleaned;
    }

	
	static function redirect($url)
	{
		$url = (string) $url;
		
		$tplSelf 	= !empty($_GET['tplSelf']) && $_GET['tplSelf'] != 0;
		$url 		= !empty($_GET['redirect']) ? $_GET['redirect'] : $url;
		
		// Prevent redirection loop
		if ( Request::url() === $url )
		{
			// TODO : make specific error page ?
			$url = _URL_HOME;
			$url .= ( strpos($url, '?') !== false ? '&' : '?' ) . 'errors=9000';
		}
		
		if ( Request::isAjax() )
		{
			$url = Tools::removeQueryParams('tplSelf', $url);
			$this->data['redirect'] = Tools::removeQueryParams('tplSelf', $url);
			return $this->render();
		}
		else
		{
			header("Location:" . $url);
			//header("Location:" . _URL . $url);
			die();
		}
	}
}

?>