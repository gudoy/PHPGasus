<?php

interface ControllerInterface
{
    public function index();
}

class Controller implements ControllerInterface
{
	public $debug 	= true;
	
	public $view 	= array();
	public $data 	= array();
	
	public function __construct($Request)
	{
		//$this->request = new ArrayObject ($Request);
		$this->request = &$Request;
		
		$this->view = new ArrayObject($this->view, 2);
		
		// If we are handling an existing resource
		if ( isset($this->request->resource) )
		{
			( isset($_resources) && isset($_columns) ) || require(_PATH_CONFIG . 'dataModel.generated.php');
			
			// Load Resource data
			$this->_resource = &$_resources[$this->request->resource];
			
			// Load Model
			$mName = ucfirst(_DB_SYSTEM) . 'Model.class.php';
			${$this->request->resource} = new $mName();
		}
	}
	
	public function dispatchMethod()
	{
		$RC = &$this->request->controller;
		
		if ( !$RC->method ){ $RC->method = 'index'; }
		
		$RC->calledMethod = $RC->method && method_exists($RC->name, $RC->method) ? $RC->method : 'error404';
		
		return call_user_func_array(array($this, $RC->calledMethod), array());
		
		/*
		
		if ( isset($args[__METHOD__]) && !$args[__METHOD__] ){ return $this; }
		
		// Known methods (alias => used)
		$known = array(
			'index' 	=> 'index',
			'put' 		=> 'update',
			'update' 	=> 'update',
			'post' 		=> 'create',
			'create'	=> 'create',
			'get' 		=> 'retrieve',
			'retrieve' 	=> 'retrieve',
			'delete' 	=> 'delete',
			'search'	=> 'search',
			'duplicate' => 'duplicate',
		);

		$id 		= !empty($args[0]) ? $args[0] : null;														// Shortcut for resource identifier(s)
		$p 			= &$params; 																				// Shortcut for params
		$allowed 	= !empty($p['allowed']) 
						? ( is_array($p['allowed']) ? $p['allowed'] : explode(',', $p['allowed']) ) 
						: array(); 																				// Get the allowed methods
		$gM 		= isset($this->options['method']) ? strtolower($this->options['method']) : null; 			// Shortcut for GET "method" param
		$pM 		= !empty($_POST['method']) 
						? strtolower(filter_var($_POST['method'], FILTER_SANITIZE_STRING)) 
						: null; 																				// Shortcut for POST "method" param
		$srM 		= isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : null; 		// Shortcut for request method
		$foundM 	= !empty($pM) ? $pM : ( !empty($gM) ? $gM : ( !empty($srM) ? $srM : null )); 				// 
		$m 			= !empty($foundM) && isset($known[$foundM]) ? $known[$foundM] : 'index';


// $this->dump('gM: ' . $gM);
// $this->dump('pM: ' . $pM);
// $this->dump('srM: ' . $srM);		
// $this->dump('found: ' . $foundM);
// $this->dump('m: ' . $m);
// $this->dump('id: ' . $id);
// $this->dump($allowed);

//var_dump($id);
//die($m);
		
		// Special case if method is 'retrieve' but resource id is not set
		// In this case, method is forced back to index 
		if ( $m === 'retrieve' && is_null($id) ) { $m = 'index'; }
		
		//$m 			= !empty($pM) 
		//					? $pM : !empty($gM) 
		//					? $gM : ( !isset($known[$srM]) || ( $known[$srM] === 'retrieve' && empty($id) ) 
		// 					? 'index' : $known[$srM] ); 														// Get the class method to use
		
		// Store the final method
		$this->data['view']['method'] = $m;
        
		// If the method is not index and belongs to the allowed methods, call it
		//if ( $m !== 'index' && in_array($m, $allowed) ) { return call_user_func_array(array($this, $m), $args); }
		if ( $m !== 'index' && in_array($m, $allowed) ) { return call_user_func_array(array($this, $m), $args); }
		// Otherwise, just continue
		else if ( $m === 'index' ) { } // just continue
		// The following case should not append
		else
		{
			return $this->statusCode(405); // Method not allowed
		}
		*/
	}

	// (string) $classname, [(string) 'shortpath']
	// array((string) $classname => (string) 'shortpath')
	public function requireLibs()
	{		
		$args 	= func_get_args();
		
		if (!is_array($args[0]) && count($args) < 2 ){ return false; }
		
		$items 	= is_array($args[0]) ? $args[0] : array($args[0] => $args[1]);
		
		foreach ( $items as $classname => $path )
		{
			//$this->requireClass(is_int($key) ? $val : $key, 'libs', is_int($key) ? '' : $val);
			class_exists($classname) || require(_PATH_LIBS . rtrim($path,'/') . '/' . $classname . '.class.php');
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
	
	public function triggerEvent()
	{
		// TODO
	}
	
	public function bind()
	{
		// TODO
	}
	
	public function render()
	{		
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
		// Extract some magic data from the request
		$this->request->getMagicData();
		
		// Merge some default properties with user defined ones 
		$this->view = new ArrayObject(array_merge(array(
			// Caching
			'cache' 					=> _APP_TEMPLATES_CACHING,
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
		
		$this->getName();
		$this->getLayout();
		$this->getTemplate();
		$this->getClasses();
		$this->getCSS();
		$this->getJS();
		
//var_dump($this->view);
//var_dump($this->view['_magic']);
//var_dump($this->view->_magic);

		$this->loadTemplate();
		
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
 
		// TODO: add groups
		
		$this->view->classes = $classes;
	}

	public function getLayout()
	{
		$this->view['layout'] = !empty($this->view['layout']) ? $this->view['layout'] : 'yours/layouts/page.' . _APP_TEMPLATES_EXTENSION;	
	}
	
	public function getTemplate()
	{
		$this->view->template = !empty($this->view['template']) ? !empty($this->view['template']) : 'yours/pages/' 
			. ( $this->request->_magic['classes'] ? join('/', $this->request->_magic['classes']) . '/' : '' )
			. $this->request->controller->calledMethod . '.' . _APP_TEMPLATES_EXTENSION;
	}
	
	public function getName()
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

		//return $this->view->$type;
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
			$url .= ( !$url ? '/public/min/?f=' : ',' ) . $item;
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
	
	public function loadTemplate()
	{
		// Variables passed to the templates 
		$passedData = array(
			'data' 		=> &$this->data,
			'request' 	=> &$this->request,
			'view' 		=> &$this->view,
		);
		
//$foo = $this->request;
//var_dump($this->request->url);
//var_dump($foo::$url);
		
		switch ( _APP_TEMPLATES_ENGINE )
		{
			case 'Haanga':
				class_exists('Haanga') || require (_PATH_HAANGA . 'lib/Haanga.php');
				
				Haanga::configure(array(
				    'template_dir' 	=> _PATH_TEMPLATES,
				    'cache_dir' 	=> _PATH_TEMPLATES_PRECOMPILED,
				));
				
				Haanga::Load($this->view['template'], $passedData);
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
					'auto_reload' 			=> _APP_TEMPLATES_FORCE_COMPILE,
					'strict_variables' 		=> false,
					'autoescape' 			=> true,
					'optimizations' 		=> -1,
				));
				
				$this->Template->addExtension(new Twig_Extensions_Extension_Text());
				$this->Template->addExtension(new Twig_Extensions_Extension_I18n());
				
				//$template->display(array('name' => 'Fabien'));
				//$template 	= $this->Template->loadTemplate($this->view['template']);
				$this->Template 		= $this->Template->loadTemplate($this->view['template']);
				echo $this->Template->render($passedData);
				break;
				
				
			case 'Smarty':
			default:
				class_exists('Smarty') || require (_PATH_SMARTY . 'Smarty.class.php');
				
				// Instanciate a Smarty object and configure it
				$this->Template 						= new Smarty();
				$this->Template->compile_check 			= _APP_TEMPLATES_COMPILE_CHECK;
				$this->Template->force_compile 			= _APP_TEMPLATES_FORCE_COMPILE;
				$this->Template->caching 				= isset($this->view['cache']) 			? $this->view['cache'] : _APP_TEMPLATES_CACHING;
				$this->Template->cache_lifetime 		= isset($this->view['cacheLifetime']) 	? $this->view['cache'] : _APP_TEMPLATES_CACHE_LIFETIME;
				$this->Template->template_dir 			= _PATH_TEMPLATES;
				$this->Template->compile_dir 			= _PATH_TEMPLATES_PRECOMPILED;
				$this->Template->cache_dir 				= _PATH_TEMPLATES_CACHE;
				//$this->Template->config_dir 			= _PATH_SMARTY . 'configs/';
				
				// Fix required since smarty 3.0.5 that use defined error reporting level by
        		//$this->Template->error_reporting  	= E_ALL & ~E_NOTICE;
        		
        		//$this->Template->allow_php_templates 	= true; // no longer allowed since smarty 3.1
				//$this->Template->allow_php_tag 		= true; // no longer allowed since smarty 3.1
				
				// Pass variables to the template & load it
				$this->Template->assign($passedData);
				$this->Template->display($this->view['template'], $cacheId);
				break;
		}
	}
	
	public function debug()
	{
		echo "<br/>\n", "DEBUG:", "<br/>\n";
		
		//echo __METHOD__, "<br/>\n";
		echo $this->request->controller->name, '::', $this->request->controller->calledMethod, "<br/>\n";
		
		// Rendering time
			global $t1;
		$renderTime = microtime(true) - $t1;
		echo 'Rendered in: ~', round($renderTime*1000,3), " ms <br/>\n";
		
		// Used memory
		global $m1;
		$m2 = memory_get_usage();
		//echo 'Men (ini): ", number_format($m1, 0, '.', ' '), " octets  <br/>\n";
		echo "Mem (start): ~ ", round($m1 / 1024, 1), " ko  <br/>\n";
		echo "Mem (end): ~ ", round($m2 / 1024, 1), " ko  <br/>\n";
		echo "Mem (used): ~ ", round(($m2 - $m1) / 1024, 1), " ko  <br/>\n";

	}
}

?>