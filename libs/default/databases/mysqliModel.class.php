<?php

class_exists('_SQLModel') || require _PATH_LIBS . 'databases/_SQLModel.class.php';

class mysqliModel extends _SQLModel
{
	
	public function connect()
	{
var_dump(__METHOD__);

		// Open a connection on the db server
		$this->db 			= new mysqli(_DB_HOST, _DB_USER, _DB_PASSWORD, _DB_NAME);
		
		// Set the timeout
		if( _DB_CONNECTION_TIMEOUT !== '' ) { $this->db->options(MYSQLI_OPT_CONNECT_TIMEOUT, _DB_CONNECTION_TIMEOUT); }
		
		// TODO: use error codes
		try 
		{
			$this->db->connect_error;
		}
		catch (Exception $e)
		{				
			// TODO: make something more user friendly. redirect to /error/;
			$err = $this->env['type'] === 'prod' ? '' : $this->db-connect_errno() . ': ' . $this->db-connect_error();
			$this->errors['DB_CONNEXION_ERROR'] = array('error' => $err);
		}

		$this->setEncoding();
				
		return $this;
	}
	
	public function setEncoding()
	{
		// Tell mysql we are sending already utf8 encoded data
		$this->db->real_query("SET NAMES 'UTF8'");
	}

}

?>