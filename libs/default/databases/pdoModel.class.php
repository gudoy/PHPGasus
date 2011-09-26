<?php

class_exists('_SQLModel') || require _PATH_LIBS . 'databases/_SQLModel.class.php';

class pdoModel extends _SQLModel
{
	
	public function connect()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);

		if 		( _DB_SYSTEM === 'mysqli' ) 	{ $driver = 'mysql'; }
		elseif 	( _DB_SYSTEM === 'postregsql' ) { $driver = 'pgsql'; }
		elseif 	( _DB_SYSTEM === 'oracle' ) 	{ $driver = 'oci'; }
		else 									{ $driver = _DB_SYSTEM; } 

		$dsn = $driver . ':host=' . _DB_HOST . ';port=' . _DB_PORT . ';dbname=' . _DB_NAME;
		
//var_dump($dsn);
$this->log($dsn);
		
		try
		{
		    $this->db = new PDO($dsn, _DB_USER, _DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
		}
		catch (PDOException $e)
		{
			// TODO: make something more user friendly. redirect to /error/;
			$err = $this->env['type'] === 'prod' ? '' : $e->getMessage();
			$this->errors['DB_CONNEXION_ERROR'] = array('error' => $err);
		}
		
//var_dump($this->db);
$this->log($this->db);
		
		return $this;	
	}
	
	public function setEncoding(){} // nothing since this is done on connection opening;
	
	public function doQuery($query, array $params = array())
	{
		$p = &$params;
		
//var_dump(__METHOD__);
$this->log(__METHOD__);
		// Log launched query
		// $this->log($query);
		// $this->logs['launched'][] = $query;
		
		if ( $p['type'] === 'select' )
		{
			$this->results = $this->db->query($query);
			$this->results->setFetchMode(PDO::FETCH_ASSOC);
		}
		else
		{
			$this->results = $this->db->exec($query); 
		}
		
//var_dump($this->results);
//$this->log(__METHOD__);
		
		$this->success = is_bool($this->results) && !$this->results ? false : true;
		
		$this->handleResults();
	}
	
	
	public function affectedRows()
	{
		$this->affectedRows = $this->success ? $this->db->affected_rows : null; 
	}
	
	public function numRows()
	{
		$this->numRows = is_object($this->results) ? $this->results->rowCount() : null;
	}
	
	public function numFields()
	{
		$this->numFields = is_object($this->results) ? $this->results->columnCount() : null;
	}
	
	public function insertedId()
	{
		$this->insertedId = $this->success ? $this->db->lastInsertId() : null;
	}
	
}

?>