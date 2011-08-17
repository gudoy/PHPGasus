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
		// TODO: bench preg_split + replacedd request uri, preg_split + redirect_url, explode + skiping '/' in dispatch
		$this->parts 		= preg_split('/\//', trim(str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']), '/'));
		//$this->parts = preg_split('/\//', trim($_SERVER['REDIRECT_URL'], '/'));
		
		$this->getURL();
		
		//$this->extension 	= strpos($uriParts['path'], '.') !== false ? preg_replace('/(.*)\.(.*)/', '$2', $uriParts['path']) : null;
		$this->extension 	= strrpos($_SERVER['REDIRECT_URL'], '.') !== false ? substr($_SERVER['REDIRECT_URL'], strrpos($_SERVER['REDIRECT_URL'], '.')+1 ) : null;
		$ext = preg_split('/\./', basename($_SERVER['REDIRECT_URL']));
		
		$this->controller = (object) array();
		
		$this->sniffPlatformData();
        $this->sniffDeviceData();
        $this->sniffBrowserData();
	}
	
	public function getLanguage($lang = null)
	{
		// Get known languages and force them into lowercase
		$known 		= defined('_APP_LANGUAGES') && is_array(_APP_LANGUAGES) 
						? explode(',', strtolower(join(',', _APP_LANGUAGES))) 
						: explode(',', strtolower(_APP_LANGUAGES));
		
		// Get  Accept-Language http header 
		// fr,fr-fr;q=0.8,en-us;q=0.5,en;q=0.3
		$accptHeader = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? str_replace('-', '_', $_SERVER['HTTP_ACCEPT_LANGUAGE']) : '';
		
		// Try to find lang in GET param
		if ( empty($lang) && in_array(strtolower($_GET['lang']), $known) )		{ $lang = strtolower($_GET['lang']); }
		
		// Try to find lang in POST param
		if ( empty($lang) && in_array(strtolower($_POST['lang']), $known) )		{ $lang = strtolower($_POST['lang']); }
		
		// Try to find lang in SESSION param
		if ( empty($lang) && in_array(strtolower($_SESSION['lang']), $known) )	{ $lang = strtolower($_SESSION['lang']); }
		
		// If the lang has not been found and if there's an Accept-Llanguage http header
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
			
			// Sort array by value
			arsort($acptLangs);
			
			// Check for match between accepted languages and known ones
			foreach ($acptLangs as $lg) { if ( in_array($lg, $known) ){ $lang = $lg; break; } }
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
		
		$_rq 			= &$this->request; 				// Shortcut for request
		$_ctr 			= &$this->controller; 			// Shortcut for request controller
		$_mg 			= &$this->_magic; 				// Shortcut for view magic data
		
		$jsSample 		= 'if ( foo && foo.init && typeof foo.init == function() ){ foo.init(); }';
		$jsSample2 		= 'if ( foo && typeof foo == function() ){ foo(); }';
		$_mg['jsCalls'] .= PHP_EOL;
		
		// Loop over the breacrumbs parts
		$camel 			= '';
		$pointed 		= '';
		foreach ( $this->breadcrumbs as $item)
		{
			$camel 				.= !empty($camel) ? ucfirst($item) : $item; 		// Get current camelcased concatenation of all parts
			$pointed 			.= !empty($pointed) ? '.' . $item : $item; 			// Get current pointed notation concatenation of all parts
			$_mg['classes'][] 	= $item; 											// Set the current item as a magic class
			$_mg['objects'][] 	= $camel;  											// Set the current concatenation as a magic object
			$_mg['jsCalls'] 	.= str_replace('foo', $pointed, $jsSample) . PHP_EOL;
		}
		
		// Add the called controller raw name
		$camel 				.= !empty($camel) ? ucfirst($_ctr->rawName) : $_ctr->rawName;
		$pointed 			.= !empty($pointed) ? '.' . $_ctr->rawName : $_ctr->rawName;
		$_mg['classes'][] 	= $_ctr->rawName; 					
		$_mg['objects'][] 	= $camel;
		$_mg['jsCalls'] 	.= str_replace('foo', $pointed, $jsSample) . PHP_EOL;
		
		// Add the called method name
		$camel 				.= !empty($camel) ? ucfirst($_ctr->calledMethod) : $_ctr->calledMethod;
		$pointed 			.= !empty($pointed) ? '.' . $_ctr->calledMethod : $_ctr->calledMethod;
		$_mg['jsCalls'] 	.= str_replace('foo', $pointed, $jsSample2) . PHP_EOL;
		
		// Set the magic view name
		$_mg['name'] 		= $camel;
		
		return $_mg;
	}

	public function sniffPlatformData()
	{
		// Default values
		$this->platform = array(
			'name' 		=> 'unknownPlatform',
			'version' 	=> 'unknownVersion',
		);
		
		// Do not continue if platform sniffing has been disabled
		if ( !_APP_SNIFF_PLATFORM ) { return $this; }
		
		// Shortcut for user agent
		$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		
		// List of known platforms
		$knownPlatforms = array(
			'Windows','Mac OS','linux','freebsd', 							// OS
			'iPhone','iPod','iPad','Android','BlackBerry','Bada','mobile', 	// Mobile
			'hpwOS', 														// Tablets
			'j2me','AdobeAIR', 												// Specific
		);
		
		foreach ( $knownPlatforms as $p )
		{
			$lower 		= strtolower($p);
			$urlParam 	= 'is' . ucfirst($lower);
			
			// Check platform identifier is present in the user agent or is the is{$platform} parameter is set in the url
			if ( strpos($ua, $p) !== false || ( isset($_GET[$urlParam]) && ( $_GET[$urlParam] === '' || $_GET[$urlParam] != false) ) )
			{
				$this->platform['name'] = str_replace(' ', '', $lower);
				
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
        $this->device  = array(
            'resolution'    => array('width' => $w, 'height' => $h),
            'isMobile'      => isset($_GET['isMobile']) 
                                ? in_array($_GET['isMobile'], array('1', 'true',1,true))
                                : ( !empty($w) ? ($w < 800) : null ),
            'orientation' => !empty($_SESSION['orientation']) 
                                ? $_SESSION['orientation'] 
                                : ( $w && $h ? ( $w > $h ? 'landscape' : 'portrait') : null),
        );
    }


	public function sniffBrowserData()
	{
		// Default values
		$this->browser 	= array(
			'engine' 		=> 'unknownEngine',
			'name' 			=> 'unknownBrowser',
			'version' 		=> array(
				'full' 			=> '?',
				'major' 		=> '?',
				'minor' 		=> '?',
				'build' 		=> '?',
				'release' 		=> '?',
			)
			//'hasHTML5' 			=> false,
		);
		
		// Do not continue if browser sniffing has been disabled
		if ( !_APP_SNIFF_BROWSER ) { return $this; }

		$ua 			= isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''; 	// Shortcut for user agent
		$_b 			= &$this->browser; 															// Shortcut for browser data
		
		// Known browsers data
		$knownEngines 	= array(
			'Trident' 		=> 'trident', 
			'MSIE' 			=> 'trident', 
			'AppleWebKit' 	=> 'webkit', 
			'Presto' 		=> 'presto', 
			'Gecko' 		=> 'gecko', 
			'KHTML' 		=> 'khtml', 
			'BlackBerry' 	=> 'mango',
			'wOSBrowser' 	=> 'webkit',
		);
		$knownBrowsers 	= array(
			'MSIE' 			=> array('name' => 'internetexplorer', 'displayName' => 'Internet Explorer', 'alias' => 'ie', 'versionPattern' => '/.*(MSIE)\s([0-9]*\.[0-9]*);.*/'),
			'Firefox' 		=> array('alias' => 'ff', 'versionPattern' => '/.*(Firefox|MozillaDeveloperPreview)\/([0-9\.]*).*/'),
			'Chrome' 		=> array('versionPattern' => '/.*(Chrome)\/([0-9\.]*)\s.*/'),
			'Safari' 		=> array('versionPattern' => '/.*(Safari|Version)\/([0-9\.]*)\s.*/'),
			'Opera' 		=> array('versionPattern' => '/.*(Version|Opera)\/([0-9\.]*)\s?.*/'),
			'Konqueror' 	=> array('versionPattern' => '/.*(Konqueror)\/([0-9\.]*)\s.*/'),
            'BlackBerry' 	=> array('versionPattern' => '/.*(BlackBerry[a-zA-Z0-9]*)\/([0-9\.]*)\s.*/'),
		);
				
		// Try to get the browser data looking in the User Agent for knonw browser keys
		foreach ($knownBrowsers as $k => $b)
		{
			if (strpos($ua, $k) !== false)
			{
				$_b = array_merge($_b, array(
					'name' 			=> !empty($b['name']) ? $b['name'] : strtolower($k),
					'identifier' 	=> $k,  
					'displayName' 	=> !empty($b['displayName']) ? $b['displayName'] : $k,
					'alias' 		=> !empty($b['alias']) ? $b['alias'] : strtolower($k),
				));
				break;
			}
		}
		
		// Try to get the browser rendering engine
		foreach ($knownEngines as $k => $e) { if (strpos($ua, $k) !== false) { $_b['engine'] = $e; break; } }
		
		// Try to get the browser version data
		if ( $_b['identifier'] && ($pattern = $knownBrowsers[$_b['identifier']]['versionPattern']) && $pattern )
		{
			$p 				= explode('.', preg_replace($pattern, '$2', $ua)); 								// Split on '.'
			array_unshift($p, join('.', $p)); 																// Insert the full version as the 1st element
			$p 				= array_pad($p, count($_b['version']), null); 									// Force the parts & default version arrays to have same length 
			$_b['version'] 	= array_merge($_b['version'], array_combine(array_keys($_b['version']), $p)); 	// Assoc default version array keys to found values
			;
		}
	}

	public function getOutputFormat()
	{
		if ( !empty($this->outputFormat) ){ return $this->outputFormat; }
        
		// Shortcut for options
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
	
	//public static function getURL()
	public function getURL()
	{
//var_dump(self);
//var_dump(self::$url);
//var_dump(self::$_instance);
//var_dump(instanceof self);
//var_dump(__METHOD__);
//var_dump('before test');
		
		//if ( isset(self::$url) ){ return self::$url; }
		if ( isset($this->url) ){ return $this->url(); }
		//if ( (self::$_instance instanceof self) && isset($this->url) ){ return $this->url(); }

//var_dump('after test');

		//if ( (self::$_instance instanceof self) && isset($this->url) ){ $this->url = $url; }
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
			if 		( strpos($tmp, "&amp;") !== false ) { $end_pos = strpos($tmp, "&amp;"); } // case where there are others params after, separated by a "&amp;"
			else if ( strpos($tmp, "&") !== false ) 	{ $end_pos = strpos($tmp, "&"); } // case where there are others params after, separated by a "&"
			else if ( strpos($tmp, "#") !== false ) 	{ $end_pos = strpos($tmp, "#"); } // case where there are others params after, separated by a "#"
			else 										{ $end_pos = strlen($tmp); } // case where there are no others params after
			
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
	
	
    /**
     * Remove params (and theirs values) from a string (or url)
     * 
     * @param string|array $paramNames name of a param or array of params name
     * @param string $replaceIn a string or URL in valid query format (param1=value1&param2=value2...)
     * @return string cleaned string
     */
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
		
		if ( self::isAjax() )
		{
			$url = Tools::removeQueryParams('tplSelf', $url);
			$this->data['redirect'] = Tools::removeQueryParams('tplSelf', $url);
			return $this->render();
		}
		else
		{
			header("Location:" . $url);
			//die();
		}
	}
}

?>