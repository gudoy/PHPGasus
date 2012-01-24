<?php

class AdminController extends Controller
{
	public function __construct(&$Request)
	{
//$this->log(__METHOD__);
//var_dump(__METHOD__);
		parent::__construct($Request);
		
		// 
		//global $_resources, $_columns, $_groups;
		//$this->_resources 	= &$_resources;
		//$this->_columns 	= &$_columns;
		//$this->_groups 		= &$_groups;
	}
	
	public function _error404()
	{
		
	}
	 
	public function index()
	{
		$this->retrieve();
	}
	
	public function retrieve()
	{
		// Shortcut to current resource name
		$_r = $this->_resource['plural'];
		
		$this->trigger('onBeforeRetrieve', array('from' => __FUNCTION__));
		
		// TODO
		// Should not have to save return value
		// Model should be instanciated with controller passed as a reference so that 
		// adding to {model}->data also add to {controller->data} 
		// Retrieve resource(s) depending of the request params (if passed)
		$this->data[$_r] = $this->{$_r}->find();
		//$this->{$_r}->find();
		
//var_dump($this->data);
$this->log(__METHOD__);
$this->log($this->data);
		
		$this->trigger('onAfterRetrieve', array('from' => __FUNCTION__));
		
		// TODO
		// If request filters have been passed but data has not been found redirect (or just call ???) to error404()
		// return $this->error404();
		// If no params (=> find all) but no data
		// return a 204???
				
		$this->render();		
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
		
		global $_groups, $_resources, $_columns;
		
		//$this->templateData['_groups'] 		= &$this->_groups; 
		//$this->templateData['_resources'] 	= &$this->_resources;
		//$this->templateData['_columns'] 		= &$this->_columns;
		
		$this->response->templateData['_groups'] 		= &$_groups;
		$this->response->templateData['_resources'] 	= &$_resources;
		$this->response->templateData['_columns'] 		= &$_columns;
	}
	
	public function getViewLayout()
	{
		$this->view['layout'] = 'yours/layouts/pageAdmin.' . _TEMPLATES_EXTENSION;	
	}
	
	public function getViewTemplate()
	{
		// If we are handling an existing resource
		if ( $this->_resource )
		{
			$this->view->template = !empty($this->view['template']) ? !empty($this->view['template']) : 'yours/pages/' 
				//. ( $this->request->_magic['classes'] ? join('/', $this->request->_magic['classes']) . '/' : '' )
				. 'admin/_resource/'
				. $this->request->controller->calledMethod . '.' . _TEMPLATES_EXTENSION;
		}
		// Otherwise, fallback to the default get template method
		else { parent::getViewTemplate(); }
	}
}

?>