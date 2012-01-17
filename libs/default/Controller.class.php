<?php

interface ControllerInterface
{
    public function index();
}

class Controller extends Core implements ControllerInterface
{	
	public $view 		= array();
	public $data 		= array();
	public $errors 		= array();
	public $warnings 	= array();
	public $success 	= null;
	
	private static $_instance;
	
	public function __construct($Request)
	{
		$this->request 	= $Request;
		$this->response = new Response(); 
		
		parent::__construct();
		
		$this->initDataModel();
		$this->initView();
		$this->initModel();
		
		$this->initEvents();
	}
	
	public function __get($prop)
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
$this->log('prop: ' . (string) $prop);
		
		# Auto-instanciation of models
		
		// Do not continue if we are not handling an existing resource
		if ( empty($prop) && !DataModel::isResource($prop) ){ return; }
		
//var_dump($prop);
//die();
		
		// Load Model
		switch(_DB_DRIVER)
		{
			case 'default':
			case 'pdo': 		$mName = 'pdoModel'; break;
			case 'mysqli': 		$mName = 'mysqliModel'; break;
			default: 			$mName = _DB_SYSTEM . 'Model'; break;
		}
		
		// Direct access to resource model
		$this->requireLibs($mName, 'databases/');
		$this->{$prop} = new $mName(array('_resource' => $prop));
		
		return $this->{$prop};
	}
	
	
	public static function getInstance()
	{
		if ( !(self::$_instance instanceof self) ) { self::$_instance = new self(); } 
		
		return self::$_instance;
	}
	
	public function initDataModel()
	{
		// Load dataModel if not already loaded
		( isset($_resources) && isset($_columns) ) || require(_PATH_CONFIG . 'dataModel.generated.php');

		// Check if the called controller is an existing resource
		if ( ($_r = DataModel::resource($this->request->controller->rawName)) && $_r )
		{
			$this->request->resource 	= $_r['name'];
			$this->_resource 			= new ArrayObject($_r, 2);
		};
	}
	
	public function initView()
	{
		// Init view
		$this->view = new ArrayObject($this->view, 2);
	}
	
	public function initModel()
	{
	}
	
	
	public function bind()
	{
		// Do not continue if events have not been instanciated
		if ( empty($this->events) || !($this->events instanceof Events) ){ }
		
		// TODO: properly forward arguments
		
		$this->events->bind();
		
		return $this;
	}
	
	public function trigger()
	{
		// Do not continue if events have not been instanciated
		if ( empty($this->events) || !($this->events instanceof Events) ){ }
		
		// TODO: properly forward arguments
		
		$this->events->trigger();
		
		return $this;
	}
	
	public function initEvents()
	{
		if ( !defined('_USE_EVENTS') || !_USE_EVENTS ){ $this->events = null; return $this; }
		
		$this->events = new Events();
	} 
	
	public function dispatchMethod()
	{
//$this->log(__METHOD__);
//$this->log($this->request);

		// Get passed arguments & extends default params with passed one
		$args 	= func_get_args();
		$p 		= !empty($args[0]) ? $args[0] : array();
		$p 		= array_merge(array(
			# Default params
			'allowedMethods' => array('')
			
		), $p, array(
			# Forced params
			
			// Allowed methods
			'allowedMethods' => array_intersect(Tools::toArray((array) $p['allowedMethods']), array('index','create','update','delete')),
			
			// Known methods
			'kownMethods' => array(
				// Native HTTP
				'get' 		=> 'index',
				'post' 		=> 'create',
				'put' 		=> 'update',
				'delete' 	=> 'delete',
				
				// Called methods
				'create' 	=> 'create',
				'update' 	=> 'update',			
			)
		));
		
		// Shortcut to request controller
		$RC = &$this->request->controller;
		 
		// Do not continue if the method name is passed in params with falsy value 
		if ( isset($p[__METHOD__]) && !$p[__METHOD__] ){ return $this; }
		
		// Try to get method from GET, POST, SERVER supersglobals (in this order)
		// Validating that it's a known method and that it is allowed for the current controller
		foreach ( array('get', 'post', 'server' => 'REQUEST_METHOD') as $k => $v )
		{
			// Get current superglobal name & index to use
			$spName = '_' . strtoupper(is_numeric($k) ? $v : $k);
			$index 	= is_numeric($k) ? 'method' : $v;
			
			// Do not continue if the proper method index is not defined in the current superglobal
			if ( empty(${$spName}[$index]) ){ continue; } 
			
			// Shortcut to request method
			$rqM = strtolower(${$spName}[$index]); 
			
			// Do not continue if the requested method is not a known one
			if ( empty($p['knownMethods'][$rqM]) ){ continue; }
			
			// Do not continue if the requested method is not a valid one for the called controller
			//if ( !in_array($p['allowedMethods'][$p['knownMethods'][$rqM]]) ){ continue; }
			
			// Otherwise, set the calledMethod
			$RC->method = $p['knownMethods'][$rqM];
		}
		
		// Check that the requested method is allowed
		// Do not continue if the requested method is not a valid one for the called controller
		//if ( !in_array($p['allowedMethods'][$p['knownMethods'][$rqM]]) ){ continue; }
		if ( !empty($RC->method) && !in_array($p['allowedMethods'][$p['knownMethods'][$rqM]]) )
		{
			// Return a 405
			$this->response->setStatuscode(405);
		}
		
		// Handle special case: if 1st request param is 'new' : method => created
		// ex: /products/new
		if ( !empty($RC->params[0]) && $RC->params[0] === 'new' )
		{
			// Check that the create method is allowed for this resource
		}
		
		// TODO: Protect against CSRF
		// If request method == get & overloaded method == delete
		//if ( strtolower($_SERVER['REQUEST_METHOD']) !== 'delete' ){ return $this->statusCode(405); }
		// If method is create/retrieve/update, generated a csrf token to we will have to check against in proper method before doing anything
		
		// If at this point the method has still not been set,
		// default it to index
		if ( !$RC->method ){ $RC->method = 'index'; }
		
		// Handle request params
		//call_user_func_array(array($this, 'dispatchParams'), $p);
		$this->dispatchParams($p);
		
		// If method exists and does not start by un '_' char (used for not exposed method)
		$RC->calledMethod = $RC->method && method_exists($RC->name, $RC->method) && $RC->method[0] !== '_' ? $RC->method : 'error404';
		
//var_dump($_SERVER);
//var_dump(__METHOD__);
//var_dump($RC);
//var_dump(get_called_class());
//var_dump($RC->calledMethod);
//die();
		
		return call_user_func_array(array($this, $RC->calledMethod), array());
	}


	// users/1 				=> findUserById(1)
	// users/1,2 			=> findUserById(array(1,2))
	// users/id/1 			=> findUserById(1)
	// users/id/1,2 		=> findUserById(array(1,2))
	// users/john 			=> findUserByNameField('john')
	// users/john,jack 		=> findUserByNameField(array('john','jack'))
	// users/john,jack 		=> findUserByNameField(array('john','jack'))
	public function dispatchParams()
	{		
		// Get passed arguments & extends default params with passed one
		$args 	= func_get_args();
		$p 		= !empty($args[0]) ? $args[0] : array();
		
		// Do not continue if the method name is passed in params with falsy value 
		if ( isset($p[__METHOD__]) && !$p[__METHOD__] ){ return $this; }
		
		// Shortcuts
		$RC = &$this->request->controller; 			// request controller
		$_r = &$this->_resource; 					// resource
		
		// TODO: handle path args
		// assume pattern is [[$column]/$value]/[...]
		// Split on '/'
		// else if is column ==> get next and add couple to filters/conditions + go to next()
		// else get current resource nameField and add to filters/conditions + go to next()
		
		// TODO: handle query strings
		
		// Loop over params
		$i = 0;
		foreach ($params as $param)
		{
			// Does the current param contains ','
			$isMulti 	= strpos($param, ',') !== false;
			
			// Split on ',' & force the result to be an array (even if the param has no ',')
			$items 		= Tools::toArray(explode(',',$param));
			
			$values 	= next($param);
			
			foreach($items as $item)
			{
				// If the item is numeric, assume it's an id
				if ( is_numeric($item) )
				{
					// TODO
					// ==> add filters/conditions + go to next()
					$this->request->filters['id'] = $item; 
				}
				// If the current resource is defined and the current item is one of it's columns
				elseif ( !empty($_r) && DataModel::isResource($_r, (string) $item) )
				{
					// TODO
					// If no values passed, assume it's a columns/getFields restricter
					if ( $values !== false )
					{
						
					}
					else
					{
						// TODO
						// => add to filters/conditions: $column => $values	
					}
				}
				// If the current resource is defined but the current item is NOT one of it's columns
				elseif ( !empty($_r) )
				{
					// Assume that the current item is a {$nameField} value to check against
					// TODO
					// => add to filters/conditions: $column => $values
				}
				else
				{
					
				}
				
				// ==> add filters/conditions + go to next()	
			}
			
			$i++;	
		}
		
var_dump(__METHOD__);
//var_dump($this->request);
//var_dump($RC);
var_dump($this);
die();
	}
	
	public function initCSRFtoken()
	{
		// Generate a CSRF token
		$_SESSION['csrftoken'] = md5(uniqid(rand(), true));	
	}
	
	public function validateCSRFtoken()
	{
		// If the CSRF token is not valid, directly render the page with proper 
		if ( !isset($_POST['csrftoken']) || !isset($_SESSION['csrftoken']) || $_POST['csrftoken'] !== $_SESSION['csrftoken']  )
		{
			$this->errors[] = INVALID_CSRF_TOKEN;
			$this->render();
		}
	}
	
	public function index()
	{
		$this->render();
	}
	
	public function error404()
	{
		$this->render();
	}
	
	public function render()
	{
//$this->log(__METHOD__);
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
		
//var_dump($this->request);
		
		$this->getViewName();
		$this->getViewLayout();
		$this->getViewTemplate();
		$this->getClasses();
		$this->getCSS();
		$this->getJS();
		
//var_dump($this->view);
		
		$this->initTemplate();
		$this->initTemplateData();
		$this->renderTemplate();
		
		$this->debug();
	}


	public function getClasses()
	{
		$_b 		= $this->request->browser;
		$classes 	= '';
		
		// Add platform, device & browser data as classes
		//$classes .= join(' ', $_rq->platform);
		//$classes .= join(' ', $_rq->device);
		$classes .= 
			//' ' . _SUBDOMAIN .
			_SUBDOMAIN .
			' ' . $this->request->platform['name'] .
			' ' . $_b['engine'] .
			' ' . $_b['alias'] .
			' ' . $_b['alias'] . $_b['version']['major'] .
			' ' . $_b['alias'] . $_b['version']['major'] . '-' . $_b['version']['minor']
			//' ' . $_b['alias'] . $_b['version']['major'] . '-' . $_b['version']['minor'] . '-' . $_b['version']['build'] .
			//' ' . $_b['alias'] . str_replace('.', '-', $_b['version']['full'])
		; 
		
 		if ( empty($this->view['classes']) )
 		{
 			$classes .= 
 				' ' . join(' ', $this->request->_magic['classes']) .
				' ' . join(' ', $this->request->_magic['objects']) .
				' ' . $this->view['name']
			;
		}

		if ( isset($_GET['emulate']) && !in_array($_GET['emulate'], array('0', 'false', 'no')) ) { $classes .= ' emulate'; }
		if ( !empty($_GET['orientation']) && in_array($_GET['orientation'], array('portrait','landscape')) ) { $classes .= ' ' . $_GET['orientation']; }
		//if ( $this->debug ) { $classes .= ' debug'; }
		if ( self::$debug ) { $classes .= ' debug'; }
 
		// TODO: add groups
		
		$this->view->classes = $classes;
	}

	public function getViewLayout()
	{
		$this->view['layout'] = !empty($this->view['layout']) ? $this->view['layout'] : 'yours/layouts/page.' . _TEMPLATES_EXTENSION;	
	}
	
	public function getViewTemplate()
	{
		$this->view->template = !empty($this->view['template']) ? !empty($this->view['template']) : 'yours/pages/' 
			. ( $this->request->_magic['classes'] ? join('/', $this->request->_magic['classes']) . '/' : '' )
			. $this->request->controller->calledMethod . '.' . _TEMPLATES_EXTENSION;
	}
	
	public function getViewName()
	{
		$this->view->name = !empty($this->view->name) ? $this->view->name : $this->request->_magic['name'];
		
		return $this->view->name;
	}
	
	public function getCSS(){ return $this->getAssets('css'); }
	public function getJS(){ return $this->getAssets('js'); }
	public function getAssets($type)
	{
		//$ret 		= array();
		$_v 		= &$this->view; 			// Shortcut for view
		$_mg 		= &$this->request->_magic; 	// Shortcut for view
		$upper 		= strtoupper($type);
		
		// If the view is explicitely specified as not containing css, or if the css param is passed with a falsy value 
		// do not continue
		if ( (isset($_v[$type]) && $v[$type] === false)
			|| ( isset($_GET[$type]) && !in_array($_GET[$type], array('0','false',0,'no')) ) ){ return $ret; }
		
		// Get group names to loop over
		// Use user defined groups if specified
		// Otherwise, get magic ones
		$groups 		= !empty($v[$type]) ? Tools::toArray($v[$type]) : array_merge( 						 
			array('common'), 
			(array) $_mg['objects'], 
			(array) $_mg['classes'],
			array($_mg['name'])
		);
		
		// Remove doubles
		$groups = array_unique($groups);
		
		// Call proper method to parse assets group
		$parseMethod = 'parse' . $upper . 'Group';
		$this->view->$type = self::$parseMethod($groups);
		
		if ( $this->view->{'minify' . $upper} )
		{
			$minMethod 			= 'getMinified' . $upper;
			$this->view->$type 	= self::$minMethod($this->view->$type);
		}
	}

	static function getMinifiedCSS($css){ return self::getMinifiedAssets('css', $css); }
	static function getMinifiedJS($js){ return self::getMinifiedAssets('js', $js); }
	static function getMinifiedAssets($type, $css)
	{
		$url = '';
		//foreach ((array) $css as $item){ $url .= ( !$url ? '/public/min/?f=' : ',' ) . constant('_URL_' . strtoupper($type) . '_REL') . $item; }
		foreach ((array) $css as $item)
		{
			// TODO: handle '&' escaping for xhtml
			//$url .= ( !$url ? '/public/min/?f=' : ',' ) . $item;
			$url .= ( !$url ? '/public/min/?f=' : ',' ) . ltrim($item, '/');
		}
		
		return array($url);
	}
	
	static function parseCSSGroup($cssGroup){ return self::parseAssetsGroup('css', $cssGroup); }
	static function parseJSGroup($jsGroup){ return self::parseAssetsGroup('js', $jsGroup); }
	static function parseAssetsGroup( $type, $assetsGroup)
	{
		// Load css file
		isset(${'_' . $type}) || require(_PATH_CONFIG . 'yours/' . $type . '.php');
		
		$_gp 		= &${'_' . $type};
		$files 		= array();
		$upper 		= strtoupper($type);
		$pattern 	= '/^.*\.(' . constant('_ALLOWED_' . $upper . '_EXT_PATTERN') . ')$/';
		
		foreach ( (array) $assetsGroup as $k => $v)
		{
			// Do not process empty values
			if 	( empty($v) ){ continue; }
			
			$isFile 		= preg_match($pattern, $v);
			$gpExists 		= !$isFile && !empty($_gp[$v]);
			$toBeRemoved 	= $isFile && strpos($v, '--') !== false;

			// If the group exists, recursively parse it and if files where returned
			$method = 'parse' . strtoupper($type) . 'Group';
			if ( $gpExists && ($gpFiles = self::$method($_gp[$v])) && $gpFiles )
			{
				// Add them
				//$files[] = $gpFiles;
				$files += $gpFiles;
			}
			// Or if the item is a file
			elseif ( $isFile )
			{
				// If the file already exists, remove it to be able to re-add it preserving cascading definition order
				// If the file is marked as to be removed (prefixed with --), do it
				// Otherwise, add the file to the final set
				if ( isset($files[$v]) ) 	{ unset($files[$v]); if ( $toBeRemoved ){ continue; } } 
				//else 						{ $files[$v] = $v; }
				//else 						{ $files[$v] = constant('_URL_' . $upper . '_REL') . $v; }
				
				else 						
				{
					// Do not append the asset type base url if the file is external (using protocol relative or http(?)//: scheme )
					$isExt = preg_match('/^(\/\/|https?:\/\/).*$/', $v);
					$files[$v] = ( $isExt ? '' : constant('_URL_' . $upper . '_REL') ) . $v;
				}
			}
		}
		
		// Remove doubles
		//$files = array_unique($files);
		
		return $files;
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

	public function initTemplateData()
	{
		// Variables passed to the templates 
		$this->templateData['data'] 	= &$this->data;
		$this->templateData['request'] 	= &$this->request;
		$this->templateData['view'] 	= &$this->view;
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