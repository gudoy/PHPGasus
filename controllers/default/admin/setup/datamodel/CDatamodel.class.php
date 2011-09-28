<?php

class CDatamodel extends AdminController
{
	public function index()
	{
		$DataModel = new DataModel();
		
		// Parse user defined dataModel
		$DataModel->parse();
		
		// Create the generated dataModel
		//$DataModel->build();
	}
}
