<?php

class CDatamodel extends AdminController
{
	public function index()
	{
var_dump(_PATH_CONF);
		
		$DataModel = new DataModel();
		
		// Parse user defined dataModel
		$DataModel->parse();
		
		// Create the generated dataModel
		//$DataModel->build();
	}
}
