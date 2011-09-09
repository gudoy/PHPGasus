<?php

class Model extends Core
{
	
	public $db 			= null;
	
	public $success 	= null;
	
	public $data 		= array();
	public $errors 		= array();
	public $warnings 	= array();
	
	public $options 	= array(
		'type' 				=> 'select', 	// 'select','insert',''update','delete'
		'limit' 			=> null, 		// (int)
		'offset' 			=> null, 		// (int)
		'orderBy' 			=> null, 		// {$datamodel $column} [asc|desc]
		'indexBy' 			=> null, 		// {gotten $column}
		'indexByUnique' 	=> null, 		// {gotten $column}
		'conditions' 		=> array(), 	//
		'groupBy' 			=> null,
		'returnning' 		=> null,
		
		'distinct' 			=> null,
		'count' 			=> array(),
		
		'by' 				=> null,
		'values' 			=> null,
		'getFields' 		=> null, // TODO: deprecate in favor if 'columns'
		'columns' 			=> null,
		
		'fetchingStrategy' 	=> null, // TODO: 
	);
	
	// 
	public $logs 		= array(
		'built' 	=> array(),
		'launched' 	=> array(),
	);
	
	//public function __construct(&$controller)
	public function __construct()
	{
		//$this->_resource = $controller->_resource;
	}
	
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
	
	public function getResources()
	{
		
	}
	
	public function retrieve(){ $this->find(); }
	public function find()
	{
var_dump(__METHOD__);

		$this->query();
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
	
	
	//public function fetchValue(){} 	// required????
	//public function fetchValues(){} 	// required????
	public function fetchCol()
	{
var_dump(__METHOD__);
	}
	public function fetchCols()
	{
var_dump(__METHOD__);
	}
	public function fetchRow()
	{
var_dump(__METHOD__);
	}
	public function fetchRows()
	{
		// TODO: get used key
		
		
var_dump(__METHOD__);
		foreach($this->results as $row)
		{
var_dump($row);
			// If indexBy && isCol(indexBy)
			// $this->data[$key] = $this->fixRow($row);
			
			// Otherwise
			$this->data[] = $this->fixRow($row);
		}
		 
	}
	
	public function fixRow(&$row)
	{
		foreach($row as $column => $value)
		{
			$row[$column] = $this->fixColumn($column, $value);
		}
		
		return $row;
	}
	
	public function fixColumn($column, $value, array $params = array())
	{
		$type 	= null;
		$v 		= &$value;
	
var_dump($this);
die();	
//var_dump(DataModel::resource())
		
        switch($type)
        {
        	# Texts
			case 'string':
			case 'varchar':
			case 'text':
			case 'slug':
			case 'tag':
			case 'html':
			case 'code':
			case 'email':
			case 'password':
			case 'url':
			case 'tel':
			case 'color':
			case 'meta':
			case 'ip':
			default:
				$v = $v; break;
			
			# Numbers
			case 'int':
			case 'integer':
			case 'smallint':
			case 'num':
			case 'number':
			case 'tinyint':
			case 'smallint':
			case 'mediumint':
			case 'bigint':
				
			case 'ai':
			case 'serial':
			case 'pk':
			case 'primarykey':
				
			# Relations
			case '1-1':
			case 'onetoone':
			case 'one2one':
			case '121':
			case '1to1':
				$v = (int) $v; break;
			
			# Booleans
			case 'bool':
			case 'boolean';
				$v = in_array($v, array(true,1,'1','true','t')) ? true : false; break;
			
			# Floats
			case 'float':
			case 'real':
			case 'double':
				$v = (float) $v;
				
			# Dates & times
            case 'timestamp':
            	$v = is_numeric($v) ? (int) $v : (int) DateTime::createFromFormat('Y-m-d H:i:s', $v, new DateTimeZone('UTC'))->format('U'); break;
            //case 'datetime':
            //case 'time':
            //case 'year':
            //case 'month':
            //case 'day':
            //case 'hours':
            //case 'minutes':
            //case 'seconds':
			
			# 
			case 'set':
				$v = !empty($v) ? explode(',', (string) $v) : array();
            case 'enum':
				$v = $v;
				
			/*
            case 'onetomany':
            case 'manytomany':
            case 'onetomany':
            
            case 'file':
            case 'fileduplicate':
                    //$v = !empty($v) && !empty($p[$colProps]['destBaseURL']) && filter_var($v, FILTER_VALIDATE_URL) === false
                            //? $p[$colProps]['destBaseURL'] . preg_replace('/^\/(.*)/','$1',$v)
                            //: $v;
            case 'image':
            case 'video':
            case 'sound':
			 */
        }
        
        return $v;
	}
}

?>