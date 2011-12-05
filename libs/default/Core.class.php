<?php

class Core
{
	private $inited = false;
	
	private static $_instance;
	//public static $debug = true;
	public static $debug = true;
	
	public function __construct()
	{
	}
	
	public function init()
	{
		if ( $this->inited ){ return; }
		
		// Now, only init debug on first time needed
		//$this->initDebug();
	}
	
	public static function getInstance()
	{
		if ( !(self::$_instance instanceof self) ) { self::$_instance = new self(); } 
		
		return self::$_instance;
	}
	
	static public function initDebug()
	{
		// Do not continue if the debug is not activated
		//if ( !$this->debug ){ return; }
		
		if ( isset($this) ){ $this->data['debug'] = array(); }
		
		// For security issues, by default, only allow debug in local & dev environments
		if ( !in_array(_APP_CONTEXT, array('local','dev')) ){ return; }
		
		// Shortcut for browser name
		//$b = $this->request->browser->name;
		// Get user agent (if exists)
		$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''; 	// Shortcut for user agent

		// http://www.chromephp.com/
		//if ( _USE_CHROMEPHP_LOGGING && $b === 'chrome' )
		if ( _USE_CHROMEPHP_LOGGING && strpos($ua, 'Chrome') !== false )
		{
//var_dump(_PATH_PUBLIC);
//return;
	        self::requireLibs('ChromePhp', 'tools/ChromePhp');
	        
			ChromePhp::useFile(_PATH_PUBLIC . 'logs/chromelogs', '/public/logs/chromelogs/');
		}
		// http://www.firephp.org/
		//elseif ( _USE_FIREPHP_LOGGING && $b === 'firefox' )
		elseif ( _USE_FIREPHP_LOGGING && strpos($ua, 'Firefox') !== false )
		{
			self::requireLibs('FirePHP', 'tools/FirePHP');	
		}
	}
	
	static public function dump($data){ return self::log($data); }
	public static function log($data)
	{
//var_dump(__METHOD__);
//var_dump(self::$debug);
//var_dump($data);
		// Do not continue if the debug is not activated
		//if ( !$this->debug ){ return; }
		//if ( !self::$debug ){ return; }
		if ( (isset($this) && !$this->debug) || !self::$debug ){ return; }
		//if ( (isset($this) && !$this->debug) || ( isset(self::$debug) && !self::$debug) ){ return; }
		
		// Get the user agent
		$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''; 	// Shortcut for user agent
		//$ua = '';
		
//var_dump($ua);
		
		if 		( _USE_CHROMEPHP_LOGGING && strpos($ua, 'Chrome/') !== false )
		{
//var_dump(class_exists('ChromePhp'));
			//return;
			class_exists('ChromePhp') || self::initDebug();
			ChromePhp::getInstance()->log($data);
		} // http://www.chromephp.com/
		elseif 	( _USE_FIREPHP_LOGGING && strpos($ua, 'Firefox/') != false ) 
		{
			class_exists('FirePHP') || self::initDebug();
			FirePHP::getInstance(true)->log($data);
		} // http://www.firephp.org/
		else 																	{ var_dump($data); }
	}

	// (string) $classname, [(string) 'shortpath']
	// array((string) $classname => (string) 'shortpath')
	static public function requireLibs()
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
	
	public function triggerEvent()
	{
		// TODO
	}
	
	public function bind()
	{
		// TODO
	}

	
	public function debug()
	{
		if ( !$this->debug ){ return; }
		
		$_phpgasus['mend'] = memory_get_usage();
		
		$this->data['debug'] = new ArrayObject(array_merge((array) $this->data->debug, array(
			'called' 		=> new ArrayObject(array(
				'controller' 	=> $this->request->controller->name,
				'method' 		=> $this->request->controller->calledMethod
			)),
			'queries' 		=> new ArrayObject(array(
				'count' 		=> null,
				'launched' 		=> array(),
				'failed' 		=> array(),
				'skipped' 		=> array(),
			), 2),
			// Times: in ms
			'time' 			=> new ArrayObject(array(
				'till_before_data' 	=> null,
				'till_after_data' 	=> null,
				'data_retrieval' 	=> null,
				'till_render' 		=> null,
				'total' 			=> round(microtime(true) - $_phpgasus['tstart'] *1000,3), 
			), 2),
			// Memory: in ko
			'memory' 		=> new ArrayObject(array(
				'onStart' 			=> round($_phpgasus['mstart'] / 1024, 1),
				'onEnd' 			=> round($_phpgasus['mend'] / 1024, 1),
				'total_used' 		=> round(($_phpgasus['mend'] - $_phpgasus['mstart']) / 1024, 1),
			), 2),
		)), 2);
		
		/*
		echo "<br/>\n", "DEBUG:", "<br/>\n";
		
		//echo __METHOD__, "<br/>\n";
		//echo $this->request->controller->name, '::', $this->request->controller->calledMethod, "<br/>\n";
		
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
		*/
	}
}

?>