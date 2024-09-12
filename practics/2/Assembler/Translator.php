<?php

class Translator {
	private $operand1;
	private $operand2;
	private $command_string;
	private $operation_type;
	private $flags = [
		'ZF' => 0, // флаг нуля
		'SF' => 0 // флаг знака
	];


	/**
	 * @param $command_string
	 */
	public function __construct($command_string) { $this->command_string = $command_string; }

	public function processCommand(&$res) {
		$this->validateCommandString();
		$executor = new Executor( $this->parseCommandType(), $this->operand1, $this->operand2 );
		$res = $executor->execute();
	}

	private function parseCommandType() {
		$cmdlets = explode(' ', $this->command_string);
		$this->operand1 = str_replace(',', '', $cmdlets[1]);
		$this->operand2 = str_replace(' ', '', $cmdlets[2]);
		foreach (COMMANDS::cases() as $command) {
			if($command->name == $cmdlets[0]) {
				return $command;
			}
		}
		throw new CommandException('Command not found' . $cmdlets[0]);
	}

	private function validateCommandString() {
		$cmdlets = explode(' ', $this->command_string);
		if(!is_array($cmdlets) || count($cmdlets) < 3) { throw new CommandException('Invalid command string ' . $this->command_string); }
	}

}

enum OPERATION_TYPES {
	case UNARY;
	case BINARY;
}