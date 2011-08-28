<?php

class pdoModel extends Model
{
	
	public function connect()
	{
var_dump(__METHOD__);
		
		try
		{
		    $this->db = new PDO('mysql:host=' . _DB_HOST . ';dbname=' . _DB_NAME, _DB_USER, _DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
		}
		catch (PDOException $e)
		{
			// TODO: make something more user friendly. redirect to /error/
			die('Database connection error. ' . ( $this->env['type'] === 'prod' ? '' : $e->getMessage() ));
		}
		
		return $this;	
	}
	
}

?>