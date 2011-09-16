<?php

class _SQLModel extends Model
{
	public function setEncoding()
	{
		// Tell mysql we are sending already utf8 encoded data
		$this->db->query("SET NAMES 'UTF8'");
	}
	
	public function query($query, array $params = array())
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
		
//var_dump($query);
$this->log(__METHOD__);
		
		$this->doQuery($query, $p);
	}
	
	public function doQuery($query, array $params = array())
	{
		$p = &$params;
		
//var_dump(__METHOD__);
$this->log(__METHOD__);
		// Log launched query
		// $this->log($query);
		// $this->logs['launched'][] = $query;
		
		$this->results = $this->db->query($query);
		
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
$this->log($this->numRows);
//var_dump($this->numFields);
$this->log($this->numFields);
		
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
		
var_dump($this->data);
//$this->log($this->data);
	}
	
	public function escapeColName(){}
	public function escapeString(){}
	
	public function getResources()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$this->data['resources'] = $this->query('SHOW TABLES');
	}

	public function buildSelect(array $params = array())
	{
		// Define shortcuts
		$this->queryPlan = array(
			'tables' 	=> array(),
			'columns' 	=> array(),
		);
		$_qp 	= &$this->queryPlan; 
		
		// Build final query  
		$_q = 	"SELECT "
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
		
		return $_q;
	}
	
	public function buildInsert()
	{
		// Define shortcuts
		$this->queryPlan = array(
			'tables' 	=> array(),
			'columns' 	=> array(),
		);
		$_qp 	= &$this->queryPlan; 
		
		// Build final query  
		$_q = 	"INSERT INTO "
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
	
	public function buildUpdate()
	{
		// Define shortcuts
		$this->queryPlan = array(
			'tables' 	=> array(),
			'columns' 	=> array(),
		);
		$_qp 	= &$this->queryPlan; 
		
		// Build final query  
		$_q = 	"UPDATE "
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
	
	
	public function buildDelete()
	{
		// Define shortcuts
		$this->queryPlan = array(
			'tables' 	=> array(),
			'columns' 	=> array(),
		);
		$_qp 	= &$this->queryPlan; 
		
		// Build final query  
		$_q = 	"DELETE "
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
		$o = &$this->options;
		
		// TODO
		
		return '';	
	}
	
	public function buildFrom()
	{
		$o = &$this->options;
		
		// TODO
		
		return '';	
	}
	
	public function buildLeftJoins()
	{
		$o = &$this->options;
		
		// TODO
		
		return '';	
	}
	
	public function buildRightJoins()
	{
		$o = &$this->options;
		
		// TODO
		
		return '';	
	}
	
	public function buildCrossJoins()
	{
		$o = &$this->options;
		
		// TODO
		
		return '';	
	}
	
	public function buildWhere()
	{
		$o = &$this->options;
		
		// TODO
		
		return '';		
	}
	
	public function buildGroupBy()
	{
		$o = &$this->options;
		
		// TODO
		
		return '';
	}
	
	public function buildOrderBy()
	{
		$o = &$this->options;
		
		// TODO
		
		return '';
	}
	
	public function buildLimit()
	{
		$o = &$this->options;
		
		!empty($o['limit']) && $o['limit'] != -1 ? "LIMIT " . $o['limit'] . " " : " ";
	}
	
	public function buildOffset()
	{
		$o = &$this->options;
		
		return !empty($o['offset']) ? "OFFSET " . $o['offset'] . " " : " ";
	}
}

?>