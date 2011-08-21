<?php

class AdminController extends Controller
{
	public function __construct(&$Request)
	{		
		parent::__construct($Request);
	}
	
	public function index()
	{
		//$this->triggerEvent('onBeforeIndex', array('from' => __FUNCTION__));
		
		// Get all (limited to _APP_LIMIT_RETRIEVED_RESOURCES) items of this resource
		${$this->_resource['name']}->find();
		
		//$this->triggerEvent('onAfterIndex', array('from' => __FUNCTION__));
		
		// Get count of total existings items for this resources
		${$this->_resource['name']}->count();
		
		// Handle pagination (get previous, next)
		
		//$this->triggerEvent('onBeforeRender', array('from' => __FUNCTION__));
		
		$this->render();
		
		//$this->triggerEvent('onAfterRender', array('from' => __FUNCTION__));
	}
	
	public function create()
	{
		// TODO
		
		$this->render();
	}
	
	public function update()
	{
		// TODO
		
		$this->render();
	}
	
	public function delete()
	{
		// TODO
		
		$this->render();
	}
	
	public function duplicate(){ $this->copy(); }
	public function copy()
	{
		// TODO
		
		$this->render();
	}
	
	public function search()
	{
		// TODO
		
		$this->render();
	}
	
	public function initTemplateData()
	{
		parent::initTemplateData();
		
		// 
		$this->templateData['_resources'] 	= &$this->_resources;
		$this->templateData['_columns'] 	= &$this->_columns;
		$this->templateData['_groups'] 		= &$this->_groups;
	}
}

?>