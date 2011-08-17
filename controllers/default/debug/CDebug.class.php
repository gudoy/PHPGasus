<?php

class CDebug extends Controller
{
	public function __construct(&$Resquest)
	{
		if ( !$this->debug ) { Request::redirect(_URL_HOME); }
		
		parent::__construct($Resquest);
	}
	
	public function index()
	{		
		// TODO: <ul> of all available functions
		
		return $this->render();
	}
	
	public function ua() { return $this->userAgent(); }
    public function userAgent()
    {
        echo $_SERVER['HTTP_USER_AGENT'];   
    }
	
	public function platform()
	{		
		if ( !_APP_SNIFF_PLATFORM ) { die('Ooops, Platform sniffing is disabled!'); }
		
		var_dump($this->request['platform']);
	}
	
	public function device()
	{		
		if ( !_APP_SNIFF_BROWSER ) { die('Ooops, Browser sniffing is disabled!'); }
		
		var_dump($this->request['device']);
	}
	
	public function browser()
	{		
		if ( !_APP_SNIFF_BROWSER ) { die('Ooops, Browser sniffing is disabled!'); }
		
		var_dump($this->request['browser']);
	}
    
	public function phpinfo(){ return $this->info(); }
	public function info()
	{		
		phpinfo();
	}
}
?>