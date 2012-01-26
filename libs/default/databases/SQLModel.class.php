<?php

class SQLModel extends Model
{
	public $queryString = '';
	
	public $uniqueOperators 	= array('>','<','>=','<=');             			// Operator whose value can only by unique
	public $oneAtATimeOperators = array('LIKE','NOT LIKE','=','!=','SOUNDS LIKE'); 	// Operators allowing multiple conditions but with only 1 at a time
	public $knownOperators 		= array( 											// Known condition operators
		'contains' 			=> 'LIKE',          // + %value% // TODO
		'like' 				=> 'LIKE',          // + %value% // TODO
		'doesnotcontains' 	=> 'NOT LIKE',      // Deprecated: typo mistake
		'doesnotcontain' 	=> 'NOT LIKE',      // + %value% // TODO
		'notlike' 			=> 'NOT LIKE',      // + %value% // TODO
		'startsby' 			=> 'LIKE',          // + value% // TODO
		'endsby' 			=> 'LIKE',          // + %value // TODO
		'doesnotstartsby' 	=> 'NOT LIKE',      // Deprecated: typo mistake
		'doesnotstartby' 	=> 'NOT LIKE',      // + value% // TODO
		'doesnotendsby' 	=> 'NOT LIKE',      // Deprecated: typo mistake
		'doesnotendby' 		=> 'NOT LIKE',      // + %value // TODO
		'not' 				=> '!=',
		'notin' 			=> 'NOT IN',
		'greater' 			=> '>',
		'>' 				=> '>',
		'lower' 			=> '<',
		'<' 				=> '<',
		'greaterorequal' 	=> '>=',
		'>=' 				=> '>=',
		'lowerorequal' 		=> '<=',
		'<=' 				=> '<=',
		'is' 				=> '=',
		'equal' 			=> '=',
		'=' 				=> '=',
		'in' 				=> 'IN',
		'isnot' 			=> '!=',
		'notequal' 			=> '!=',
		'!=' 				=> '!=',
		'notin' 			=> 'NOT IN',
		'between' 			=> 'BETWEEN',       // TODO
		'notbetween' 		=> 'NOT BETWEEN', 	//
		'soundslike' 		=> 'SOUNDS LIKE',
		'match' 			=> 'MATCH', 		// + AGAINST(). Only for MyISAM tables
		'search' 			=> 'MATCH', 		// + AGAINST(). Only for MyISAM tables
	);
	
	public function find()
	{
//var_dump($query);
$this->log(__METHOD__);
		$args = func_get_args();
		$this->handleOptions($args);

		$this->buildQuery();
		$this->query();
		
		return $this->data;
	}
	
	
	public function setEncoding()
	{
		// Tell mysql we are sending already utf8 encoded data
		$this->db->query("SET NAMES 'UTF8'");
	}
	
	public function query(array $params = array())
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		// If the connection is not opened, open it
		if ( !$this->db ){ $this->connect(); }
		
		// Extends default params by passed ones
		$p = array_merge(array(
			'type' 		=> 'select',
			'prepared' 	=> false,
		), $params);
		
		// TODO: handle prepared queries
		
		$this->doQuery($p);
		
		//return $this->data;
	}
	
	public function doQuery(array $params = array())
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$p = &$params;

		// Log launched query
		// $this->log($query);
		// $this->logs['launched'][] = $query;
		
		$this->results = $this->db->query($this->queryString);
		
		$this->success = is_bool($this->results) && !$this->results ? false : true;
		
		$this->handleResults();
	}
	
	
	public function handleResults(array $params = array())
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		// Do not continue if the request did not returned results
		if ( !$this->success ){ return; }
		
		$p = &$params; 
    	
		$this->numRows();
		$this->numFields();
		
//var_dump($this->numRows);
$this->log('numrows: ' . $this->numRows);
//var_dump($this->numFields);
$this->log('numfields: ' . $this->numFields);
		
		if ( $p['type'] === 'insert' )
		{
			$this->getinsertedId();
			$this->affectedRows();
		}
		elseif ( $p['type'] === 'udpate' )
		{
			$this->affectedRows();
		}
		
		$this->fetchResults();
	}
	
	public function affectedRows()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		$this->affectedRows = $this->success ? $this->db->affected_rows : null; 
	}
	
	public function numRows()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$this->numRows = is_object($this->results) ? $this->results->num_rows : null;
	}
	
	public function numFields()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		$this->numFields = is_object($this->results) ? $this->results->field_count : null;
	}
	
	public function insertedId()
	{
		$this->insertedId = $this->success ? $this->db->insert_id : null;
	}
	
	public function fetchResults()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
//var_dump($this);
//die();

		// TODO: use requested cols/rows number to decide which method to use to fetch results???
		# Conditions are in order of use frequency
		
		// X row, X cols
		if ( $this->numRows > 1 ) 									{ $this->fetchRows(); }
		// 1 row, 1 col
		elseif ( $this->numRows === 1 && $this->numFields === 1 )	{ $this->fetchCol(); }
		// 1 row, X cols
		elseif ( $this->numRows === 1 && $this->numFields > 1 ) 	{ $this->fetchRow(); }
		// X row, 1 col
		elseif ( $this->numRows > 1 && $this->numFields > 1 )		{ $this->fetchCols(); }
		
//var_dump($this->data);
$this->log($this->data);
	}
	
	public function escapeColName(){}
	public function escape($string)
	{
		return '`' . (string) $string . '`';
	}
	
	public function getResources()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$this->data['resources'] = $this->query('SHOW TABLES');
	}
	
	
	public function buildQuery(array $params = array())
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		$p = array_merge(array(
			'type' => 'select',
		), $params);
		
		switch ($p['type'])
		{
			case 'insert': 	$this->buildInsert(); break;
			case 'update':	$this->buildUpdate(); break;
			case 'delete': 	$this->buildDelete(); break;
			
			// TODO
			//case 'upsert': 	$this->buildUpsert(); break;
			
			case 'select': 
			default: 		$this->buildSelect(); break;
		}
		
$this->log('builtQuery: ' . $this->queryString);
	}
	

	public function buildSelect(array $params = array())
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		// Define shortcuts
		$this->queryPlan = array(
			'tables' 	=> array(),
			'columns' 	=> array(),
		);
		$qp 	= &$this->queryPlan; 
		
		// Build final query  
		$this->queryString = 
			"SELECT "
				. $this->buildColumnsList()
				. $this->buildFrom()
				. $this->buildLeftJoins()
				. $this->buildRightJoins()
				. $this->buildCrossJoins()
				. $this->buildWhere()
				. $this->buildGroupBy()
				. $this->buildOrderBy()
				. $this->buildLimit()
				. $this->buildOffset()
		;
		
		//return $qp;
	}
	
	public function buildInsert()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		// Define shortcuts
		$this->queryPlan = array(
			'tables' 	=> array(),
			'columns' 	=> array(),
		);
		$qp 	= &$this->queryPlan; 
		
		// Build final query
		$this->queryString = 
			"INSERT INTO "
				. $this->buildFrom()
				. $this->buildColumnsList()
				. $this->buildLeftJoins()
				. $this->buildRightJoins()
				. $this->buildCrossJoins()
				. $this->buildWhere()
				. $this->buildGroupBy()
				. $this->buildOrderBy()
				. $this->buildLimit()
				. $this->buildOffset()
		;
		
		return $qp;
	}
	
	public function buildUpdate()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		// Define shortcuts
		$this->queryPlan = array(
			'tables' 	=> array(),
			'columns' 	=> array(),
		);
		$qp 	= &$this->queryPlan; 
		
		// Build final query  
		$this->queryString = 
			"UPDATE "
				. $this->buildFrom()
				. $this->buildColumnsList()
				. $this->buildLeftJoins()
				. $this->buildRightJoins()
				. $this->buildCrossJoins()
				. $this->buildWhere()
				. $this->buildGroupBy()
				. $this->buildOrderBy()
				. $this->buildLimit()
				. $this->buildOffset()
		;
	}
	
	
	public function buildDelete()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		// Define shortcuts
		$this->queryPlan = array(
			'tables' 	=> array(),
			'columns' 	=> array(),
		);
		$qp 	= &$this->queryPlan; 
		
		// Build final query  
		$this->queryString = 
			"DELETE "
				. $this->buildFrom()
				. $this->buildColumnsList()
				. $this->buildLeftJoins()
				. $this->buildRightJoins()
				. $this->buildCrossJoins()
				. $this->buildWhere()
				. $this->buildGroupBy()
				. $this->buildOrderBy()
				. $this->buildLimit()
				. $this->buildOffset()
		;
		
		return $_q;
	}
	

	public function buildColumnsList()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$o 	= &$this->options;
		$_r = &$this->_resource;
		
		// TODO
		
		return $this->escape($_r['alias']) . '.* ';	
	}
	
	public function buildFrom()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$o 	= &$this->options;
		$_r = &$this->_resource;
		
		// TODO
		
		return 'FROM ' 
			. $this->escape($_r['table']) . ' AS ' . $this->escape($_r['alias']) . ' ';	
	}
	
	public function buildLeftJoins()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$o = &$this->options;
		
		// TODO
		
		return '';	
	}
	
	public function buildRightJoins()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$o = &$this->options;
		
		// TODO
		
		return '';	
	}
	
	public function buildCrossJoins()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$o = &$this->options;
		
		// TODO
		
		return '';	
	}
	
	public function buildWhere()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);

$this->log($this->options['conditions']);

		// Initialize conditions request outptut
		$output = '';
				
		// Do not continue if there's no conditions to handle 
		//if ( empty($o['conditions']) ) { return $output; }
		
		return '';		
	}
	
	public function buildGroupBy()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$o = &$this->options;
		
		// TODO
		
		return '';
	}
	
	public function buildOrderBy()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$o = &$this->options;
		
		// TODO
		
		return '';
	}
	
	public function buildLimit()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$o = &$this->options;
		
		!empty($o['limit']) && $o['limit'] != -1 ? "LIMIT " . $o['limit'] . " " : " ";
	}
	
	public function buildOffset()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$o = &$this->options;
		
		return !empty($o['offset']) ? "OFFSET " . $o['offset'] . " " : " ";
	}
	
	
	public function escape()
	{
	}
	
	public function escapeString($string)
	{
		$string = !empty($string) ? (string) $string : '';
		
		$this->db->real_escape_string($string);
	}
	
	
	public function handleQueryTypes($val, $options = array())
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$o            = array_merge(array(
			'resource'   => null,
			'column'     => null,
			'operator'   => null,
		), $options);
		
		// TODO: get column type in datamodel
		$type 		= 'string';
		
		// TODO
		$valPrefix = '';
		$valSuffix = '';
		
		/*
		$res          = $o['resource'];
		$col          = $o['column'];
		$colModel     = !empty($res) && !empty($col) && !empty($this->application->dataModel[$res][$col]) ? $this->application->dataModel[$res][$col] : null;
		$type      		= !empty($colModel['type']) ? $colModel['type'] : null;
		$valPrefix    = !empty($o['operator']) && in_array($o['operator'], array('contains','like','doesnotcontains','notlike','endsby','doesnotendsby','doesnotendby')) ? '%' : '';
		$valSuffix    = !empty($o['operator']) && in_array($o['operator'], array('contains','like','doesnotcontains','notlike','startsby','doesnotstartsby','doesnotstartby')) ? '%' : '';
		 */
		
		if 		( $type === 'timestamp' && !is_null($val) ) 		{ $val = "FROM_UNIXTIME('" . $this->escapeString($val) . "')"; }
		else if ( $type === 'bool'  ) 								{ $val = in_array($val, array(1,true,'1','true','t'), true) ? 1 : 0; }
		else if ( is_int($val) ) 									{ $val = (int) $val; }
		else if ( is_float($val) ) 									{ $val = (float) $val; }
		else if ( is_bool($val) ) 									{ $val = (int) $val; }
		else if ( is_null($val) || strtolower($val === 'null') ) 	{ $val = 'NULL'; }
		else if ( is_string($val) ) 								{ $val = "'" . $valPrefix . $this->escapeString($val) . $valSuffix . "'"; }
		
		return $val;
	}
}

?>