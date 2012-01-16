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
	 
	public function index()
	{
//die(__METHOD__);
//$this->log(__METHOD__);
		//$this->triggerEvent('onBeforeIndex', array('from' => __FUNCTION__));
	
//var_dump($this);
//$this->log($this);
		
		$rName 				= $this->_resource['plural']; // Shortcut to current resource name
		
//var_dump($this->{$rName});
		
		//$this->data[$rName] = $this->{$rName}->findAll();
		$this->data[$rName] = $this->{$rName}->query('SELECT * FROM resources');
		
		// Get all (limited to _APP_LIMIT_RETRIEVED_RESOURCES) items of this resource
		//$this->{$this->_resource['plural']}->find();
		
		//$this->trigger('onAfterIndex', array('from' => __FUNCTION__));
		
		// Get count of total existings items for this resources
		//$this->{$this->_resource['plural']}->count();
		
		// Handle pagination (get previous, next)
		
		//$this->trigger('onBeforeRender', array('from' => __FUNCTION__));
		
		$this->render();
		
		//$this->trigger('onAfterRender', array('from' => __FUNCTION__));
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
		
		$this->templateData['_groups'] 		= &$_groups;
		$this->templateData['_resources'] 	= &$_resources;
		$this->templateData['_columns'] 	= &$_columns;
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