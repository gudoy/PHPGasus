<?php

Class Account extends Core
{
	public function __construct()
	{
		
	}
	
	private function isLogged()
	{
		// return true/false	
	}
	
	private function login()
	{		
		$redirect 	= !empty($_POST['redirect']) 
						? Tools::sanitize($_POST['redirect'], array('type' => 'uri')) 
						: ( !empty($_GET['redirect']) ? $_GET['redirect'] : null );
		$url 		= $redirect ? _URL . $redirect : _URL_HOME;
		
		// If already logged
		if ( $this->logged() ){ $this->redirect($url); }		
	}
	
	private function logout()
	{
		$redirect 	= !empty($_POST['redirect']) 
						? Tools::sanitize($_POST['redirect'], array('type' => 'uri')) 
						: ( !empty($_GET['redirect']) ? $_GET['redirect'] : null );
		$url 		= $redirect ? _URL . $redirect : _URL_LOGIN;
		
		// Delete DB session
		if ( !empty($_SESSION['id']) ) { CSessions::getInstance()->delete(array('values' => $_SESSION['id'])); }
		
		// Destroy cookie session if used (default)
		if ( ini_get('session.use_cookies') )
		{
	    	$p 		= session_get_cookie_params();
			$time 	= !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time();
	    	//setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $params['httponly']);
			setcookie(session_name(), '', $time - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
		}
		
		// Empty, delete & finally destroy session session var
		$_SESSION = array();
		unset($_SESSION);
		session_destroy();
		
		// 
		return $this->redirect($url);
	}
	
	private function getLoginAttempsCount()
	{
		$_SESSION['login_attemps'] = isset($_SESSION['login_attemps']) ? $_SESSION['login_attemps']+1 : 0;
		
		return $_SESSION['login_attemps'];
	}
	
	private function increaseLoginAttempsCount()
	{
		
		$this->getLoginAttempsCount()++;
	}
	
	private function resetLoginAttemptsCount()
	{
		//unset($_SESSION['login_attemps']);
		$_SESSION['login_attemps'] = 0;
	}
	
	private function isLogged(){}
	private function requireLogin(){}
	
	private function ban(){}
	private function unban(){}
	private function isBannned(){}
	
	private function isAuth(array $params = array())
	{
		// If groups passed as a param, check against
		// $this>
	}
	private function requireAuth()
	{
		// URL = passed or default to HOME
		
		// If not, redirect to URL
		// $this->redirect()	
	}
	
	private function belongsToGroup($name)
	{
		return $this->belongsToGroups(array((string) $name));	
	}
	private function belongsToGroups($names)
	{
		$names = Tools::toArray($names);
	}
}
