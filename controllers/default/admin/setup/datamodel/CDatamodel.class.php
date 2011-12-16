<?php

class CDatamodel extends AdminController
{
	public function index()
	{
//var_dump(__METHOD__);
//die(__METHOD__);
//$this->log(__METHOD__);
		$DataModel = new DataModel();
		
		// Parse user defined dataModel
		$DataModel->parse();
		
//var_dump(__METHOD__);
		
		// Create the generated dataModel
		$DataModel->build();
	}
}
