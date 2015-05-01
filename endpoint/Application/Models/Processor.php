<?php

use ElephantIO\Client,
	ElephantIO\Engine\SocketIO\Version1X;

class Model_Processor
{
	private $_socket = null;
	
	public function __construct()
	{
		$hostname = 'localhost';
		$port = 5667;
		
		$this->_socket = new Client(new Version1X(sprintf("https://%s:%s", $hostname, $port)));		
	}
	
	public function processMessage($data)
	{
		$this->getSocket()->initialize();
		$this->getSocket()->emit('processMessage', $data);
		$this->getSocket()->close();
	}
	
	private function getSocket() { return $this->_socket; }
}