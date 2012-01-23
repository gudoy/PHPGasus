<?php

class Response extends Core
{
	public $httpVersion = '1.1';
	
	// Default status code to 200 OK
	public $statusCode = 200;
	
	// Known status codes
	// http://www.w3.org/Protocols/rfc2616/rfc2616-sec6.html
	// http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
	public $statusCodes = array(
		
		// Information
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		118 => 'Connection timed out',
		
		
		// Success
		200 => 'OK',
		201 => 'Created',				
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		
		// Redirection
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'User Proxy',
		307 => 'Temporary Redirect',
		
		// Client error
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Time-out',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested range unsatisfiable',
		417 => 'Expectation Failed',
		
		// Server error
		500 => 'Internal server error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service unavailable',
		504 => 'Gateway Time-out',
		505 => 'HTTP Version not supported',
	);
	
	// formats params: mime, headers, ...
	public $outputFormats = array(
		'html' 			=> array('mime' => 'text/html'),
		'xhtml' 		=> array('mime' => 'application/xhtml+xml'),
		'json' 			=> array('mime' => 'application/json'),
		'jsonp' 		=> array('mime' => 'application/json'),
		'jsontxt' 		=> array('mime' => 'text/plain' ),
		'jsonpreport' 	=> array('mime' => 'application/json'),
		'xml' 			=> array('mime' => 'application/xml'),
		'xmltxt' 		=> array('mime' => 'text/plain'),
		'plist' 		=> array('mime' => 'application/plist+xml'),
		'plisttxt' 		=> array('mime' => 'text/plain'),
		'yaml' 			=> array('mime' => 'text/yaml'),
		'yamltxt' 		=> array('mime' => 'text/plain'),
		'qr' 			=> array('mime' => 'image/png'),
		// TODO
		//'php' 			=> array('mime' => 'vnd.php.serialized'),
		//'phptxt' 			=> array('mime' => 'text/plain'),
		//'csv' 			=> array('mime' => 'text/csv'),
		//'rss' 			=> array('mime' => 'application/rss+xml'),
		//'atom' 			=> array('mime' => 'application/atom+xml'),
		//'rdf' 			=> array('mime' => 'application/rdf+xml'),
		//'zip' 			=> array('mime' => 'application/rdf+xml'),
		//'gz' 				=> array('mime' => 'multipart/x-gzip'),
	);
	
	public $headers = array();
				
	public function __construct(Controller $Controller)
	{
//var_dump($Controller);
		
		$this->controller 	= &$Controller;
		$this->request 		= &$Controller->request;
		$this->view 		= &$Controller->view;
		
		$this->init();
	}
	
	public function init()
	{
		$this->inited = true;
	}
	
	public function setSatusCode(int $code)
	{
		// Do not continue if the passed statuscode is unknown
		if ( empty($this->$statusCodes[$code]) ){ return $this; } 
		
		$this->statusCode = $code;
		
		$this->headers[] = 'HTTP/' . $this->httpVersion . ' ' . $this->statusCode;
		
		// TODO: if status === 204, render directly ?
		
		return $this;
	}
	
	
	public function setHeader(string $name, $value)
	{
		$this->headers[] = $name . ': ' . $value;
		
		return $this;
	}
	
	
	public function writeHeaders()
	{
		foreach ($this->headers as $item){ header($item); }
		
		return $this;
	}
	
	
	
	public function render()
	{
var_dump(__METHOD__);
		
		$renderMethod = 'render' . strtoupper($this->request->getOutputFormat());
	
		$this->$renderMethod();	
	}
	
	public function renderJSON()
	{
		// TODO
	}
	
	public function renderJSONP()
	{
		// TODO
	}
	
	public function renderHTML()
	{
//$this->log(__METHOD__);
var_dump(__METHOD__);
		
		// Extract some magic data from the request
		$this->request->getMagicData();
		
		// Merge some default properties with user defined ones 
		$this->view = new ArrayObject(array_merge(array(
			// Caching
			'cache' 					=> _TEMPLATES_CACHING,
			'cacheId' 					=> null,
			'cacheLifetime' 			=> null,
			
			// Metas
			'title' 					=> null,
			'metas' 					=> array(),
			'description' 				=> _APP_META_KEYWORDS,
			'keywords' 					=> _APP_META_KEYWORDS,
			'htmlAttributes' 			=> null,
			'robotsArchivable' 			=> _APP_META_ROBOTS_ARCHIVABLE,
			'robotsIndexable' 			=> _APP_META_ROBOTS_INDEXABLE,
			'robotsImagesIndexable' 	=> _APP_META_ROBOTS_IMAGES_INDEXABLE,
			'googleTranslatable' 		=> _APP_META_GOOGLE_TRANSLATABLE,
			'refresh' 					=> null,
			'allowPrerendering' 		=> _APP_ALLOW_PAGE_PRERENDERING,
			
			// Viewport
			'iosWebappCapable' 			=> _APP_IOS_WEBAPP_CAPABLE,
			'viewportWidth' 			=> _APP_VIEWPORT_WIDTH,
			'viewportIniScale' 			=> _APP_VIEWPORT_INI_SCALE,
			'viewportMaxScale' 			=> _APP_VIEWPORT_MAX_SCALE,
			'viewportUserScalable' 		=> _APP_VIEWPORT_USER_SCALABLE,
			
			'minifyCSS' 				=> _MINIFY_CSS,
			'minifyJS' 					=> _MINIFY_JS,
			'minifyHTML' 				=> _MINIFY_HTML,
		), (array) $this->view,
		array(
			'minifyCSS' 				=> isset($_GET['minify']) ? in_array($_GET['minify'], array('css','all')) : $this->view->minifyCSS,
			'minifyJS' 					=> isset($_GET['minify']) ? in_array($_GET['minify'], array('js','all')) : $this->view->minifyJS,
			'minifyHTML' 				=> isset($_GET['minify']) ? in_array($_GET['minify'], array('html','all')) : $this->view->minifyHTML,
		)), 2);
		
		$this->controller->getViewName();
		$this->controller->getViewLayout();
		$this->controller->getViewTemplate();
		$this->controller->getClasses();
		$this->controller->getCSS();
		$this->controller->getJS();
		
		$this->initTemplate();
		$this->controller->initTemplateData();
		$this->renderTemplate();
		
		$this->debug();
	}
	
	public function initTemplate()
	{		
		switch ( _TEMPLATES_ENGINE )
		{
			case 'Haanga':
				
				class_exists('Haanga') || require (_PATH_HAANGA . 'lib/Haanga.php');
				
				Haanga::configure(array(
				    'template_dir' 	=> _PATH_TEMPLATES,
				    'cache_dir' 	=> _PATH_TEMPLATES_PRECOMPILED,
				));
				break;
			
			case 'Twig':
				
				class_exists('Twig_Autoloader') || require (_PATH_TWIG . 'lib/Twig/Autoloader.php');
				Twig_Autoloader::register();
				
				class_exists('Twig_Extensions_Autoloader') || require (_PATH_TWIG . 'lib/Twig/Extensions/Autoloader.php');
				Twig_Extensions_Autoloader::register();
				
				//$loader 	= new Twig_Loader_String();
				$loader 				= new Twig_Loader_Filesystem(_PATH_TEMPLATES);
				$this->Template 		= new Twig_Environment($loader, array(
					'debug' 				=> false,
					'charset' 				=> 'utf-8',
					'base_template_class' 	=> 'Twig_Template',
					'cache' 				=> _PATH_TEMPLATES_PRECOMPILED,
					'auto_reload' 			=> _TEMPLATES_FORCE_COMPILE,
					'strict_variables' 		=> false,
					'autoescape' 			=> true,
					'optimizations' 		=> -1,
				));
				
				$this->Template->addExtension(new Twig_Extensions_Extension_Text());
				$this->Template->addExtension(new Twig_Extensions_Extension_I18n());
				break;
				
			case 'Smarty':
			default:
				
				class_exists('Smarty') || require (_PATH_SMARTY . 'Smarty.class.php');
				
				// Instanciate a Smarty object and configure it
				$this->Template 						= new Smarty();
				$this->Template->compile_check 			= _TEMPLATES_COMPILE_CHECK;
				$this->Template->force_compile 			= _TEMPLATES_FORCE_COMPILE;
				$this->Template->caching 				= isset($this->view['cache']) 			? $this->view['cache'] : _TEMPLATES_CACHING;
				$this->Template->cache_lifetime 		= isset($this->view['cacheLifetime']) 	? $this->view['cache'] : _TEMPLATES_CACHE_LIFETIME;
				$this->Template->template_dir 			= _PATH_TEMPLATES;
				$this->Template->compile_dir 			= _PATH_TEMPLATES_PRECOMPILED;
				$this->Template->cache_dir 				= _PATH_TEMPLATES_CACHE;
				//$this->Template->config_dir 			= _PATH_SMARTY . 'configs/';
				
				// Fix required since smarty 3.0.5 that use defined error reporting level by
        		//$this->Template->error_reporting  	= E_ALL & ~E_NOTICE;
        		
        		//$this->Template->allow_php_templates 	= true; // no longer allowed since smarty 3.1
				//$this->Template->allow_php_tag 		= true; // no longer allowed since smarty 3.1
				break;
		}

		$this->templateData = array();
	}


	public function renderTemplate()
	{
$this->log(__METHOD__);
$this->log($this->templateData);
		
		// Pass variables to the template & render it
		switch ( _TEMPLATES_ENGINE )
		{
			case 'Haanga':
				Haanga::Load($this->view['template'], $this->templateData);
				break;
			case 'Twig':
				$this->Template = $this->Template->loadTemplate($this->view['template']);
				echo $this->Template->render($this->templateData);
				break;
			case 'Smarty':
			default:
				$this->Template->assign($this->templateData);
				$this->Template->display($this->view['template'], $cacheId);
				break;
		}
	}
}

?>