<?php

class_exists('SQLModel') || require _PATH_LIBS . 'databases/SQLModel.class.php';

class pdoModel extends SQLModel
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
$this->log('dsn: ' . $dsn);
		
		try
		{
			// Setting character encoding as an init command only works in mysql
		    //$this->db = new PDO($dsn, _DB_USER, _DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
		    $this->db = new PDO($dsn, _DB_USER, _DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
		}
		catch (PDOException $e)
		{
			// TODO: make something more user friendly. redirect to /error/;
			$err = $this->env['type'] === 'prod' ? '' : $e->getMessage();
			$this->errors['DB_CONNEXION_ERROR'] = array('error' => $err);
		}
		
//var_dump($this->db);
//$this->log($this->db);
//$this->log($err);
		
		return $this;	
	}
	
	public function setEncoding()
	{
		// Tell the db we are sending already utf8 encoded data
		$this->db->query("SET CHARACTER SET utf8'");
	}
	
	public function escapeString($string)
	{
		$string = !empty($string) ? (string) $string : '';
		
		return $this->db->quote($string);
	}
	
	public function doQuery(array $params = array())
	{
//var_dump($query);
$this->log(__METHOD__);

//$this->log('builtQuery: ' . (string) $this->queryString);
		
		// Do not continue if there's no querySting
		if ( !$this->queryString ){ return; } 
		
		$p = &$params;
		
		// Log launched query
		// $this->log($query);
		// $this->logs['launched'][] = $query;
		
		if ( $p['type'] === 'select' )
		{
			$this->results = $this->db->query($this->queryString);
			
//var_dump($this->results);
			
			// 
			if ( $this->results ) { $this->results->setFetchMode(PDO::FETCH_ASSOC); }
		}
		else
		{
			$this->results = $this->db->exec($this->queryString); 
		}
		
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