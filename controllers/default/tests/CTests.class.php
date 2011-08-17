<?php

class CTests extends Controller
{
	public function index()
	{		
		$this->render();
	}
	 
	 
	 public function arrayobject()
	 {
		$view = new ArrayObject(array(
			'foo' => 'bar',
			'bar' => 'foo',
		), 2);
var_dump($view);
var_dump(count($view));
var_dump($view->{'foo'});
		$view['newItem1'] = 1;
		$view['newItem2'] = 2;
		$view->newItem3 = 3;
var_dump($view);
var_dump(count($view));
var_dump('newItem1: ' . $view->newItem1);
var_dump('newItem1: ' . $view['newItem1']);
var_dump('newItem4: ' . $view->newItem3);

		$view[] = 'numIndexItem';
var_dump($view);
var_dump(count($view));

		var_dump('test foreach 1');
		foreach($view as $k => $v)
		{
			var_dump($k . ': ' . $v);
		}
		
		var_dump('test foreach 2');
		foreach((array) $view as $k => $v)
		{
			var_dump($k . ': ' . $v);
		}
		
var_dump(isset($view->newItem1));
var_dump(empty($view->newItem1));
var_dump(isset($view['newItem1']));
var_dump(empty($view['newItem1']));

		// TODO: test $view += array();
		// TODO: test array_merge();
	 }
	 
	 public function merge()
	 {
		$view = new ArrayObject(array(
			'foo' => 'bar',
			'bar' => 'foo',
		), 2);
		
		$foo = array('foobar', 'baz', 'foobaz' => 1);
		
		var_dump(array_merge((array) $view), (array) $foo);
	 }
}

?>