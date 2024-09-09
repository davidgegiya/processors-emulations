<?php

class Register {
	private $value;

	public function __construct($initialValue = 0) {
		$this->value = $initialValue;
	}

	public function read() {
		return $this->value;
	}

	public function write($value) {
		$this->value = $value;
	}
}