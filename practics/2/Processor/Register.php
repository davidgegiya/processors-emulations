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
		$cmd1 = new Translator('mov ' . $this->value . ', ' . $value);
		$cmd1->processCommand($this->value);
	}
}