<?php

class CHome extends Controller
{
	public function index()
	{
//var_dump(Request::getURL());
//var_dump($this->request);
//var_dump($this->request->getURL());
//var_dump($this->request->getURL());

//$this->view['foo'] 	= 'bar';  // OK
//$this->view->bar 	= 'foobar'; // OK
		//$this->view += array('foo' => 'bar', 'bar' => 'foo'); // FAIL, cause $this->view is an ArrayObject
		//$this->view = array_merge((array) $this->view, array('foo' => 'bar', 'bar' => 'foo'));  // OK
		
		$this->render();
	}
	 
	public function maintenance()
	{
		$this->render();
	}
 
 
	public function down()
	{
		$this->render();
	}
}

?>