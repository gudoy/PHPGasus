<?php

class Model extends Core
{
	public function __call($method, $args)
    {
//var_dump(__METHOD__);
//var_dump($method);
//var_dump($args);

		// PATTERN: verb[Limiters][offseters][restricters][conditioners][condition operator][sorters]
		
		$verbs = array(
			'find' 				=> 'select',
			'get'				=> 'select',
			'select' 			=> 'select',
								// 'retrieve' will be used if limiter = one|first|last 
			
			'update' 			=> 'update',
			
			//'createOrUpdate' 	=> 'upsert',
			//'updateOrCreate' 	=> 'upsert',
			//'upsert' 			=> 'upsert',
			
			'remove' 			=> 'delete',
			'delete' 			=> 'delete',
		);
		
		$limiters 		= array(
			// {$nb}
			'one' 			=> array('action' => 'retrieve', 'limit' => 1),
			'all'			=> array('limit' => -1),
			'first' 		=> array('limit' => 1, 'sortBy' => 'ASC'), // + use default oderBy column (default to id)
			'last' 			=> array('limit' => 1, 'sortBy' => 'DESC'), // + use default oderBy column (default to id)
		);
		
		$offseters 		= array(
			'before' 		=> array(), // TODO
			'after' 		=> array(), // TODO
		);
		
		$restricters 	= array(
			//{$colName}
			'distinct' 		=> array(), // TODO
		);
		
		$conditioners 	= array(
			'by',
			'where',
			'with',
			'whose', + 
			'which',
			'whom',
			'and',
			'or',
			
		);
		
		$conditionOperators = array(
			// use model condition operators
			'having',
			'maching',
			'verifying',
		);
			
		// Default request options	
		$opts = array(
			'getFields' 	=> '', 		// TODO: deprecate. rename into 'columns'
			'limit' 		=> null,
			'conditions' 	=> array(), // reset or extends
		);
		
		//$parts = preg_split('/(?<!^)(?=[A-Z])/', $foo, -1, PREG_SPLIT_OFFSET_CAPTURE);
		$parts = preg_split('/(?=[A-Z])/', $method, -1, PREG_SPLIT_NO_EMPTY);
		
//var_dump($parts);
		
		// Loop over the parts
		$i = 0;
		foreach($parts as $part)
		{
			$lower = strtolower($part);
			
			# Verb
			// Check that the verb is a known/allowed one
			if ( $i === 0 && !isset($verbs[$lower]) ){ return; } // TODO: how to handle errors????
			$method = $verbs[$lower];
			
			# Limiters
			// If the part is a number, assume use it as a limit
			if 		( is_numeric($part) ){ $opts['limit'] = (int) $part; }
			// Otherwise, check if it's a known limiter
			else if ( in_array($lower, $limiters) )
			{
				$opts = array_merge($opts + $limiters[$lower]);
			}
			
			# Offseters
			
			# Restricters
			
			# Conditioners
			
			# Sorters
			 
			$i++;
		}

		return $this->$method($opts);
    }

	public function connect()
	{

	}
	
	public function create()
	{
		
	}
	
	public function retrieve(){ $this->find(); }
	public function find()
	{
//$this->log(__METHOD__);
	}
	
	public function update()
	{
		
	}
	
	public function delete()
	{
		
	}
	
	public function createOrUpdate(){ $this->upsert(); }
	public function upsert()
	{
		
	}
	
	
	public function select()
	{
		$args = func_get_args();
		
		
	}
	
	private function buildSelect()
	{
		$args = func_get_args();
		
		$p = array_merge(array(
			// Default params
			'type' 		=> 'select',
			'limit' 	=> _APP_LIMIT_RETRIEVED_RESOURCES,
			
			//'count' 	=> null,
			//'distinct' 	=> null,
		), $args);
	}
	
	private function query()
	{
		
	}
	
	public static function count()
	{
		//$this->data['total'][$this->resource['name']] = $this->select(array('count' => 'id', 'limit' => -1));
		// 
	}
}

?>