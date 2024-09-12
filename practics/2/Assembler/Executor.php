<?php

class Executor {
	private $flags = [
		'ZF' => 0, // флаг нуля
		'SF' => 0, // флаг знака
	];

	private $operand1;
	private $operand2;
	private $command;

	/**
	 * @param $operand1
	 * @param $operand2
	 * @param $command
	 */
	public function __construct( $command, $operand1, $operand2 ) {
		$this->operand1 = $operand1;
		$this->operand2 = $operand2;
		$this->command = $command;
	}


	public function execute() {
		$this->resetFlags();
		switch ($this->command) {
			case COMMANDS::mov:
				$res = $this->mov();
				break;
			case COMMANDS::add:
				$res = $this->add();
				break;
			case COMMANDS::sub:
				$res = $this->sub();
				break;
			case COMMANDS::cmp:
				$res = $this->cmp();
				break;
		}
		return $res;
	}

	private function resetFlags() {
		$this->flags = [
			'ZF' => 0,
			'SF' => 0,
		];
	}

	private function mov() {
		return $this->operand2;
	}

	private function add() {
		return $this->operand1 + $this->operand2;
	}

	private function sub() {
		$res = $this->operand1 - $this->operand2;
		if (!$res) {
			$this->flags['ZF'] = 1;
		} else if ($res < 0) {
			$this->flags['SF'] = 1;
		}
		return $res;
	}

	private function cmp() {
		$this->sub();
		if($this->flags['ZF'] === 1) {
			return EQUALITY::eq;
		} elseif($this->flags['SF'] === 1) {
			return EQUALITY::lt;
		} else {
			return EQUALITY::gt;
		}
	}
}