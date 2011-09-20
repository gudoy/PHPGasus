<?php

class CAbout extends Controller
{	
	public function index($options = null)
	{
		/*	
		$this->view = array_merge($this->view, array(
			'name' 			=> 'about',
			'current' 		=> array('menu' => 'about'),
			'title' 		=> _APP_TITLE . ' - ' . ucfirst(gettext('about us')),
		));*/
		
		$this->render();
	}
	
	
	public function contact($options = null)
	{
		//$this->requireLibs(array('MathCaptcha' => 'security/'));
		
		/*
		$this->view = array_merge($this->view, array(
			'current' 			=> array('menu' => 'contact'),
			'errorsBlock' 		=> false,
			'title' 			=> _APP_TITLE . ' - ' . ucfirst(_('contact us')),
		));
		*/
		
		/*
		if ( !empty($_POST) )
		{
			$this->requireControllers('CContacts');
			$CContacts = new CContacts();
			
			$CContacts->handleContactMail();
			$this->data['success'] = $CContacts->success;
			$this->data['errors'] = $CContacts->errors;
			
			if ( $this->data['success'] ){ $_POST = null; }
		}*/
		
		//if ( !$this->data['success'] ){ $data['view']['captchaOperation'] = MathCaptcha::create(); }
		
		$this->render();
	}
	
	
	public function credits()
	{
		$this->render();
	}
	
	
};

?>