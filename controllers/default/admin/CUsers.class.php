<?php

class CUsers extends Controller
{
	public function index()
	{
		$this->render();
	}
	
	public function create($options = array())
	{
		$this->userId = parent::create($options);
		
		// If the user creation succeed
		if ( $this->success && _APP_USE_ACCOUNTS_CONFIRMATION ){ $this->sendConfirmationMail(); }
		
		return $this->userId;
	}
	
	public function sendConfirmationMail()
	{
		// If the user id is not found do not continue;
		if ( !$this->userId ){ return; }
		
		$user = $this->retrieve(array('conditions' => array('id' => $this->userId)));
		
		// Generate an unique key and insert it into the db
		$key 	= Tools::generateUniqueID(array('length' => 32, 'resource' => 'users', 'field' => 'activation_key'));
		$user 	= array_merge($user, array('activation_key' => $key));
		$_POST 	= array('activation_key' => $key);
		$this->update(array('isApi' => 1, 'conditions' => array('id' => $user['id'])));
		
		$Mailer 		= new Mailer($this->application);
		$from 			= _APP_OWNER_CONTACT_MAIL;
		$to 			= $user['email'];
		$subject 		= '[' . _APP_TITLE . '] ' . _('Please activate your account');
		$content 		= $Mailer->fetch(array(
			'data' 		=> array('users' => $user), 
			'template' 	=> _PATH_TEMPLATES . 'specific/mails/account/activate.tpl'
		));

		// Send the mail				
		$Mailer->send(array('from' => $from, 'to' => $to, 'subject' => $subject, 'content' => $content));
	}
	
	public function sendResetPasswordMail($userId)
	{
		// Generate an unique key and insert it into the db
		$time 	= !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time(); 
		$key 	= sha1(md5($time));
		$_POST 	= array('password_reset_key' => $key);
		$this->update(array('isApi' => 1, 'conditions' => array('id' => $userId)));
		
		$user = $this->retrieve(array('conditions' => array('id' => $userId)));
		
		$Mailer 		= new Mailer($this->application);
		$from 			= _APP_OWNER_CONTACT_MAIL;
		$to 			= $user['email'];
		$subject 		= '[' . _APP_TITLE . '] ' . _('Reset your password');
		$content 		= $Mailer->fetch(array(
			'data' 		=> array('users' => $user), 
			'template' 	=> _PATH_TEMPLATES . 'specific/mails/account/password/lost.tpl'
		));

		// Send the mail				
		$Mailer->send(array('from' => $from, 'to' => $to, 'subject' => $subject, 'content' => $content));
	}
	
	public function resetPassword()
	{
		// TODO
	}
}
?>