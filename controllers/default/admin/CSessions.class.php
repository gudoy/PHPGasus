<?php

class CSessions extends Controller
{
	public function index()
	{
		$this->render();
	}
	
	// TODO: check that the session is valid
	public function _check(){}
	
	// TODO: update session expiration_date to time() + _SESSION_DURATION
	public function _ping(){}
	
	public function delete()
	{
		parent::delete();
	}
}
?>