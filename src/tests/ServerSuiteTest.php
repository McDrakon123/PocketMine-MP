<?php

class ServerSuiteTest extends PHPUnit_Framework_TestCase{
	private $server;
	public function hook(){
		$this->assertTrue(true);
		
		$this->server->close();
	}

	public function testRead(){
	
		//binary things
		$this->assertTrue(Utils::readTriad("\x02\x01\x03") === 131331, "Utils::readTriad");
		$this->assertTrue(Utils::readInt("\xff\x02\x01\x03") === -16645885, "Utils::readInt");
		$this->assertTrue(abs(Utils::readFloat("\x49\x02\x01\x03") - 532496.1875) < 0.0001, "Utils::readFloat");
		$this->assertTrue(abs(Utils::readDouble("\x41\x02\x03\x04\x05\x06\x07\x08") - 147552.5024529) < 0.0001, "Utils::readDouble");
		$this->assertTrue(Utils::readLong("\x41\x02\x03\x04\x05\x06\x07\x08") === "4684309878217770760", "Utils::readLong");	
		//PocketMine-MP server startup
		require_once(dirname(__FILE__)."/../dependencies.php");
		require_once(FILE_PATH."/src/functions.php");
		require_once(FILE_PATH."/src/dependencies.php");
		$this->server = new ServerAPI();
		$this->assertTrue(is_integer($this->server->event("server.start", array($this, "hook"))));
		$this->server->start();
		
		kill(getmypid()); //Fix for ConsoleAPI being blocked
		exit(0);
	}
}