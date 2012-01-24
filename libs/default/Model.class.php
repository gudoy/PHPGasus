<?php

class Model extends Core
{
	
	public $db 			= null; 
	
	//public $success 	= null;
	
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
	
	// Model should be instanciated with controller passed as a reference so that 
	// adding to {model}->data also add to {controller->data}
	public function __construct(Controller &$Controller, array $params = array())
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);

		$p = &$params;
		
		// Set reference to controller members
		//$this->data 		= &$Controller->data;
		$this->errors 		= &$Controller->errors;
		$this->warnings 	= &$Controller->warnings;
		$this->_resource 	= &$Controller->_resource;

		//if ( !($this->_resource = DataModel::resource($params['_resource']))) { return false; }
		if ( !DataModel::isResource($p['resource']) ){ return false; }
		
		$this->_resource = new ArrayObject(DataModel::resource($p['resource'], 2));
		
		// Shortcut to current resource columns
		$this->_resourcecolumns = DataModel::columns($p['resource']);
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
			'count' 		=> array(), // TODO
			'distinct' 		=> array(), // TODO
		);
		
		$columns 		= array(); // TODO
		
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
		
		$orderers = array(
			'sortedBy' 				=> array(), // TODO
			'indexedBy' 			=> array(), // TODO
			'indexedByUnique' 		=> array(), // TODO
			// 'groupedBy' 			=> array(), // TODO ???
			// 'groupedByUnique' 	=> array(), // TODO ???
			// 'regroupedBy' 		=> array(), // TODO ???
			// 'regroupedByUnique' 	=> array(), // TODO ???
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
	
	public function create()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);

		$this->handleOptions();
	}
	
	//public function retrieve(){ $this->find(); }
	public function find()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
	}
	
	public function update()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);

		$this->handleOptions();		
	}
	
	public function delete()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);

		$this->handleOptions();
	}
	
	public function createOrUpdate(){ $this->upsert(); }
	public function upsert()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);

		$this->handleOptions();
	}
	
	public function count()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);

		$this->handleOptions();
	}
	public function distinct()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);

		$this->handleOptions();
	}
	
	
	public function handleOptions()
	{
		# Handle Indexes
		// Check that the passed index are existing columns
		if 		( !empty($o['indexByUnique']) && !DataModel::isColumn($o['indexByUnique']) ) 	{ $o['indexByUnique'] 	= null; }
		elseif 	( !empty($o['indexBy']) && !DataModel::isColumn($o['indexBy']) )				{ $o['indexBy'] 		= null; }
		
		# Handle orderBy
		
		# Handle conditions
	}
	
	
	
	//public function fetchValue(){} 	// required????
	//public function fetchValues(){} 	// required????
	public function fetchCol()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
	}
	public function fetchCols()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
	}
	public function fetchRow()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
	}
	public function fetchRows()
	{
		$o 	= &$this->options;
		
//var_dump($this);
				
//var_dump(__METHOD__);
$this->log(__METHOD__);
		$i = 0;
		foreach($this->results as $row)
		{
//var_dump($row);			
			$this->fixRow($row);
			
			if ( $o['indexByUnique'] && isset($row[$o['indexByUnique']]) )
			{
				$this->data[$o['indexByUnique']] = $row;
			}
			else if ( $o['indexBy'] && isset($row[$o['indexBy']]) )
			{
				$this->data[$o['indexBy']][] = $row;
			}
			else
			{
				$this->data[$i] = $row;	
			}
			
			$i++;
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
		//$type 	= null;
		$v 		= &$value;
		
//var_dump(__METHOD__  . ' : ' . $column . ' : ' . $this->_resourcecolumns[$column]['type']);
$this->log(__METHOD__  . ' : ' . $column . ' : ' . $this->_resourcecolumns[$column]['type']);
//$this->log(__METHOD__);
		
        switch($this->_resourcecolumns[$column]['type'])
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
//var_dump(is_numeric($v));
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