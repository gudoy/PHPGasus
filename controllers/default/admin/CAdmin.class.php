<?php

class CAdmin extends AdminController
{
	public function index()
	{
		Request::redirect(_URL_ADMIN_DASHBOARD);
		//$this->render();
	}
}

?>