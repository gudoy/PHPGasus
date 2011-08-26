<?php

class mysqliModel extends Model
{
	
	public function connect()
	{
		// Open a connection on the db server
		$this->db 			= new mysqli(_DB_HOST, _DB_USER, _DB_PASSWORD, _DB_NAME);
		
		// Set the timeout
		if( _DB_CONNECTION_TIMEOUT !== '' ) { $this->db->options(MYSQLI_OPT_CONNECT_TIMEOUT, _DB_CONNECTION_TIMEOUT); }
		
		// TODO: use error codes
		if ( $this->db->connect_error )
		{
			// TODO: make something more user friendly. redirect to /error/
			die('Database connection error. ' . ( $this->env['type'] === 'prod' 
				? '' 
				: $this->db-connect_errno() . ': ' . $this->db-connect_error() ));
		}
		
		// Tell mysql we are sending already utf8 encoded data
		$this->db->real_query("SET NAMES 'UTF8'");
		
		return $this;
	}
}

?>