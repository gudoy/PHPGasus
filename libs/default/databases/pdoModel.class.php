<?php

class_exists('_SQLModel') || require _PATH_LIBS . 'databases/_SQLModel.class.php';

class pdoModel extends _SQLModel
{
	
	public function connect()
	{
var_dump(__METHOD__);

		$dsn = 'mysql:host=' . _DB_HOST . ';port=' . _DB_PORT . ';dbname=' . _DB_NAME;
		
//var_dump($dsn);
//var_dump(_DB_USER);
		
		try
		{
		    $this->db = new PDO($dsn, _DB_USER, _DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
		}
		catch (PDOException $e)
		{
			// TODO: make something more user friendly. redirect to /error/;
			$err = $this->env['type'] === 'prod' ? '' : $this->db-connect_errno() . ': ' . $this->db-connect_error();
			$this->errors['DB_CONNEXION_ERROR'] = array('error' => $err);
		}
		
var_dump($this->db);

//var_dump($this->db->errorCode());
var_dump($this->db->errorInfo());
		
		return $this;	
	}
	
	public function setEncoding(){} // nothing since this is done on connection opening;
	
	public function doQuery($query, array $params)
	{
var_dump(__METHOD__);
		// Log launched query
		// $this->log($query);
		// $this->logs['launched'][] = $query;
		
		if ( $p['type'] === 'select' )
		{
			$this->results = $this->db->query($query);
		}
		else
		{
			$this->results = $this->db->exec($query); 
		}
	}
	
	public function fetchResults()
	{
var_dump(__METHOD__);
var_dump($this->results);

        while ($row = $this->results->fetch(PDO::FETCH_ASSOC))
        {
var_dump($row);
		}
	}
	
}

?>