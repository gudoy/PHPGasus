<?php

interface ControllerInterface
{
    public function index();
}

class Controller extends Core implements ControllerInterface
{
	public $request 	= null;
	public $response 	= null;
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
//$this->log(__METHOD__);
//$this->log('prop: ' . (string) $prop);
		
		# Auto-instanciation of models
		
		// Do not continue if we are not handling an existing resource
		if ( empty($prop) && !DataModel::isResource($prop) ){ return; }
		
//var_dump($prop);
//die();
		
		$this->initModel(array('resource' => $prop));
		
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
		$args 	= func_get_args();
		$p 		= !empty($args[0]) ? $args[0] : array(
			'resource' => $this->_resource->name,
		);
		
		// Get resource
		
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
		$this->{$p['resource']} = new $mName($this, array('resource' => $p['resource']));
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
		
		// Do not continue if the method name is passed in params with falsy value 
		if ( isset($p[__METHOD__]) && !$p[__METHOD__] ){ return $this; }
		
		// Extends passed params
		$p 		= array_merge($p, array(
			// Allowed methods
			'allowed' => isset($p['allowed']) 
				? array_intersect(Tools::toArray((array) $p['allowed']), array('index','create','update','delete'))
				: array('index','create','update','delete'),
			
			// Known methods
			'known' => array(
				// Native HTTP
				'get' 		=> 'index',
				'post' 		=> 'create',
				'put' 		=> 'update',
				'delete' 	=> 'delete',
				//'head' 		=> 'index', // TODO: how to handle
				
				// Called methods
				'create' 	=> 'create',
				'update' 	=> 'update',			
			)
		));
		
		// Shortcut to request controller
		$_rqc = &$this->request->controller;
		
		// Handle request params
		//call_user_func_array(array($this, 'dispatchParams'), $p);
		$this->dispatchParams($p);
		
		// Try to get REST method from GET, POST, SERVER supersglobals (in this order)
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
			if ( empty($p['known'][$rqM]) ){ continue; }
			
			// Do not continue if the requested method is not a valid one for the called controller
			//if ( !in_array($p['allowed'][$p['known'][$rqM]]) ){ continue; }
			
			// Otherwise, set the calledMethod
			$_rqc->method 		= $p['known'][$rqM];
		}

//var_dump(__METHOD__);		
//var_dump($_rqc);
		
		// Check that the requested method is allowed
		// Do not continue if the requested method is not a valid one for the called controller
		if ( !empty($_rqc->method) && !in_array($p['allowed'][$p['known'][$rqM]]) )
		{
			// Return a 405
			$this->response->setStatusCode(405);
		}
		
		// Handle special case: if 1st request param is 'new' : method => created
		// ex: /products/new
		if ( !empty($_rqc->params[0]) && $_rqc->params[0] === 'new' )
		{
			// Check that the create method is allowed for this resource
		}
		
		// TODO: Protect against CSRF
		// If request method == get & overloaded method == delete
		//if ( strtolower($_SERVER['REQUEST_METHOD']) !== 'delete' ){ return $this->response->setStatusCode(405); }
		// If method is create/retrieve/update, generated a csrf token to we will have to check against in proper method before doing anything
		
		// If at this point the method has still not been set,
		// default it to index
		//if ( !$_rqc->method ){ $_rqc->method = 'index'; }
		 
		// If method exists and does not start by un '_' char (used for not exposed method)
		
		// Otherwise
		$_rqc->calledMethod = !empty($_rqc->method) && method_exists($_rqc->name, $_rqc->method) && $_rqc->method[0] !== '_'
			// Redirect to it 
			? $_rqc->method 
			// If the current controller is an existing resource
			// redirect to 'index' method, otherwise redirect to _error404 method
			: ( empty($this->_resource) ? '_error404' : 'index' );
		
		return call_user_func_array(array($this, $_rqc->calledMethod), array());
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
		$_rq 	= &$this->request;
		$_rqc 	= &$this->request->controller; 			// request controller
		$rName 	= &$this->_resource->name; 				// resource
		$params = &$_rqc->params;

//var_dump($_r);
//var_dump('params');
//var_dump($params);
		
		// Loop over params
		$i = 0;
		foreach ((array) $params as $param)
		{
//var_dump($param);
			
			// Does the current param contains ','
			$isMulti 	= strpos($param, ',') !== false;
			
//var_dump('isMulti: ' . (int) $isMulti);
			
			// Split on ',' & force the result to be an array (even if the param has no ',')
			$items 		= Tools::toArray(explode(',',$param));

//var_dump('items (arrayfied)');			
//var_dump($items);
			
			$values 	= next($params);

//var_dump('values:');
//var_dump($values);
			
			foreach($items as $item)
			{
//var_dump((int) DataModel::isColumn($_r->name, (string) $item));
//var_dump('is column: ' . $item . ' : ' . (int) DataModel::isColumn($_r, (string) $item));
				
				// If the item is numeric, assume it's an id
				if ( is_numeric($item) )
				{
//var_dump('case id (is numeric): ' . $item);
					// TODO
					// ==> add filters/conditions + go to next()
					//$_rq->filters['id'] = $item;
//var_dump($_rq->filters);
					$_rq->filters['id'] = !empty($_rq->filters['id']) ? (array) $_rq->filters['id'] : array();
					$_rq->filters['id'][] = $item;
					
					// TODO: remove duplicates? (ex, /users/1,3,1)
				}
				// If the current resource is defined and the current item is one of it's columns
				elseif ( !empty($rName) && DataModel::isColumn($rName, (string) $item) )
				{
					// TODO
					// If no values passed, assume it's a columns/getFields restricter
					if ( $values !== false )
					{
//var_dump('case resource column with values: ' . $item);
						$_rq->filters[$item] = !empty($rName->filters[$item]) ? (array) $rName->filters[$item] : array();
						$_rq->filters[$item][] = Tools::toArray($values);
					}
					else
					{
//var_dump('case resource column WITHOUT values: ' . $item);
//var_dump($this->request['columns']);
						// Restrict gotten columns to passed one(s) 
						//$_rq->restricters[] = 'distinct';
						$_rq->columns[] 	= $item;
					}
				}
				// If the current resource is defined and has a nameField
				// but the current item is NOT one of it's columns 
				elseif ( !empty($this->_resource) && !empty($this->_resource->nameField) )
				{
					// Assume that the current item is a {$nameField} value to check against
					$_rq->filters[$nameField] = !empty($_rq->filters[$nameField]) ? (array) $_rq->filters[$nameField] : array();
					$_rq->filters[$nameField][] = $item;
				}
				else
				{
					
				}
				
				// ==> add filters/conditions + go to next()	
			}
			
			$i++;	
		}
		
//var_dump(__METHOD__);
//var_dump($this);
//var_dump($this->request);
//var_dump($RC);
//var_dump($this);
//die();
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
	
	public function _error404()
	{
		$this->render();
	}
	
	public function render()
	{
		$this->trigger('onBeforeRender', array('from' => __FUNCTION__));
		
		$renderMethod = 'render' . strtoupper($this->request->getOutputFormat());
	
		$this->$renderMethod();
		
		$this->trigger('onAfterRender', array('from' => __FUNCTION__));
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
		
		$this->getViewName();
		$this->getViewLayout();
		$this->getViewTemplate();
		$this->getClasses();
		$this->getCSS();
		$this->getJS();
		
		$this->initTemplate();
		$this->initTemplateData();
		$this->renderTemplate();
		
		$this->debug();
	}


	public function getClasses()
	{
		$b 			= $this->request->browser;
		$classes 	= '';
		
		// Add platform, device & browser data as classes
		//$classes .= join(' ', $_rq->platform);
		//$classes .= join(' ', $_rq->device);
		$classes .= 
			//' ' . _SUBDOMAIN .
			_SUBDOMAIN .
			' ' . $this->request->platform['name'] .
			' ' . $b['engine'] .
			' ' . $b['alias'] .
			' ' . $b['alias'] . $b['version']['major'] .
			' ' . $b['alias'] . $b['version']['major'] . '-' . $b['version']['minor']
			//' ' . $b['alias'] . $b['version']['major'] . '-' . $b['version']['minor'] . '-' . $b['version']['build'] .
			//' ' . $b['alias'] . str_replace('.', '-', $b['version']['full'])
		; 
		
 		if ( empty($this->view['classes']) )
 		{
 			$classes .= 
 				' ' . join(' ', $this->request->_magic['classes']) .
				' ' . join(' ', $this->request->_magic['objects']) .
				' ' . $this->view['name']
			;
		}

		// 
		if ( isset($_GET['emulate']) && !in_array($_GET['emulate'], array('0', 'false', 'no')) ) { $classes .= ' emulate'; }
		
		// 
		if ( !empty($_GET['orientation']) && in_array($_GET['orientation'], array('portrait','landscape')) ) { $classes .= ' ' . $_GET['orientation']; }
		
		// 
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
		$this->view->template = !empty($this->view['template']) 
			? !empty($this->view['template']) 
			: 'yours/pages/' 
				. ( $this->request->_magic['classes'] ? join('/', $this->request->_magic['classes']) . '/' : '' )
				. $this->request->controller->calledMethod . '.' . _TEMPLATES_EXTENSION;
	}
	
	public function getViewName()
	{
		// Shortcut for view
		$_v 		= &$this->view;
		
		$_v->name 	= !empty($_v->name) ? $_v->name : $this->request->_magic['name'];
		
		return $this->view->name;
	}
	
	public function getCSS(){ return $this->getAssets('css'); }
	public function getJS()	{ return $this->getAssets('js'); }
	public function getAssets($type)
	{
		$_v 			= &$this->view; 			// Shortcut for view
		$_mg 			= &$this->request->_magic; 	// Shortcut request 'magic' data
		$upper 			= strtoupper($type);
		
		// If the view is explicitely specified as not containing css, or if the css param is passed with a falsy value 
		// do not continue
		if ( (isset($_v[$type]) && $v[$type] === false)
			|| ( isset($_GET[$type]) && !in_array($_GET[$type], array('0','false',0,'no')) ) ){ return $ret; }
		
		// Get group names to loop over
		// Use user defined groups if specified
		// Otherwise, get magic ones
		$groups 		= !empty($_v[$type]) ? Tools::toArray($_v[$type]) : array_merge( 						 
			array('common'), 
			(array) $_mg['objects'], 
			(array) $_mg['classes'],
			array($_mg['name'])
		);
		
		// Remove doubles
		$groups 		= array_unique($groups);
		
		// Call proper method to parse assets group
		$parseMethod 	= 'parse' . $upper . 'Group';
		$_v->$type 		= self::$parseMethod($groups);
		
		if ( $_v->{'minify' . $upper} )
		{
			$minMethod 	= 'getMinified' . $upper;
			$_v->$type 	= self::$minMethod($_v->$type);
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