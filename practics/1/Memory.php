<?php

class Memory {
	private $data = [];

	public function __construct($data = []) {
		$this->data = $data;
	}

	public function read($address) {
		return $this->data[$address] ?? 0;
	}

	public function write($address, $value) {
		$this->data[$address] = $value;
	}

	public function getData() {
		return $this->data;
	}
}