<?php

class _SQLModel extends Model
{
	public function setEncoding()
	{
		// Tell mysql we are sending already utf8 encoded data
		$this->db->query("SET NAMES 'UTF8'");
	}
	
	public function query($query, $params = array())
	{
var_dump(__METHOD__);
		
		// If the connection is not opened, open it
		if ( !$this->db ){ $this->connect(); }
		
		// Extends default params by passed ones
		$p = array_merge(array(
			'type' 		=> 'select',
			'prepared' 	=> false,
		), $params);
		
		// TODO: handle prepared queries
		
var_dump($query);
		
		$this->doQuery($query, $p);
		
		$this->fetchResults();
	}
	
	public function doQuery($query, array $params)
	{
var_dump(__METHOD__);
		// Log launched query
		// $this->log($query);
		// $this->logs['launched'][] = $query;
		
		$this->results = $this->db->query($query);
		
		$this->success = is_bool($this->results) && !$this->results ? false : true;
	}
	
	
	public function buildSelect($params = array())
	{
		// Extends default params by passed ones
		$p = array_merge(array(
			// Default params
			'type' 		=> 'select',
			'limit' 	=> _APP_LIMIT_RETRIEVED_RESOURCES,
			
			//'count' 	=> null,
			//'distinct' 	=> null,
		), $params);
	}
	
	public function fetchResults()
	{
var_dump(__METHOD__);

		// Single value
		// Returnning
		// 1 row, 1 col
		// 1 row, X cols
		// X row, 1 col
		// X row, X cols 
	}
	
	public function fetchValue(){}
	public function fetchValues(){}
	public function fetchCol(){}
	public function fetchCols(){}
	public function fetchRow(){}
	public function fetchRows(){}
	
	public function escape(){}
	
	public function getResources()
	{
var_dump(__METHOD__);
		
		$this->data['resources'] = $this->query('SHOW TABLES');
	}
	
	public function count(){}
	public function distinct(){}
	
	public function buildConditions(){}
	public function buildLimit(){}
	public function buildGroupBy(){}
}

?>