<?php

// Usage
//$this->errors->get(); 							// array()
//$this->errors->add('MISSING_REQUIRED_FIELD'); 	// true
//$this->errors->get(); 							// array('MISSING_REQUIRED_FIELD' => {error_data})
//$this->errors->add('WRONG_CREDENTIALS'); 			// true
//$this->errors->get(); 							// array('MISSING_REQUIRED_FIELD' => {error_data}, 'WRONG_CREDENTIALS' => {error_data} )
//$this->errors->add(false); 						// false
//$this->errors->add(42); 							// false
//$this->errors->add('FOOBAR'); 					// false


// TODO: include default + yours errors


// TOOD: make extends notification (extends core)
class Errors extends Core
{
	public function __construct()
	{
	}
	
	public function get()
	{
		$args = func_get_args();
		
		// If no error key is passed, return all the errors
	}
	
	public function add($errorKey, array $params = array())
	{
		// If error exists
		
			// If passed data
		
		// otherwise, return an exception?
	}
}

?>	