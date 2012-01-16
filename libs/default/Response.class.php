<?php

class Response
{
	public $httpVersion = '1.1';
	
	// Default status code to 200 OK
	public $statusCode = 200;
	
	// Known status codes
	// http://www.w3.org/Protocols/rfc2616/rfc2616-sec6.html
	// http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
	public $statusCodes = array(
		
		// Information
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		118 => 'Connection timed out',
		
		
		// Success
		200 => 'OK',
		201 => 'Created',				
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		
		// Redirection
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'User Proxy',
		307 => 'Temporary Redirect',
		
		// Client error
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Time-out',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested range unsatisfiable',
		417 => 'Expectation Failed',
		
		// Server error
		500 => 'Internal server error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service unavailable',
		504 => 'Gateway Time-out',
		505 => 'HTTP Version not supported',
	);
	
	public $headers = array();
				
	public function __construct()
	{
	}
	
	public function init()
	{
		
	}
	
	public function setSatusCode(int $code)
	{
		// Do not continue if the passed statuscode is unknown
		if ( empty($this->$statusCodes[$code]) ){ return $this; } 
		
		$this->statusCode = $code;
		
		$this->headers[] = 'HTTP/' . $this->httpVersion . ' ' . $this->statusCode;
		
		// TODO: if status === 204, render directly ?
		
		return $this;
	}
	
	
	public function setHeader(string $name, $value)
	{
		$this->headers[] = $name . ': ' . $value;
		
		return $this;
	}
	
	
	public function writeHeaders()
	{
		foreach ($this->headers as $item){ header($item); }
		
		return $this;
	}
	
	
	public function render()
	{
		
	}
}

?>