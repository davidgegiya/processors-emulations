<?php

class Translator {
	private $operand1;
	private $operand2;
	private $command_string;


	/**
	 * @param $command_string
	 */
	public function __construct($command_string) { $this->command_string = $command_string; }

	public function processCommand() {
		$executor = new Executor( $this->parseCommandType(), $this->operand1, $this->operand2 );
		return $executor->execute();
	}

	private function parseCommandType() {
		$cmdlets = explode(' ', $this->command_string);
		$this->operand1 = str_replace(',', '', $cmdlets[1] ?? '');
		$this->operand1 = (int)str_replace('R', '', $cmdlets[1] ?? '');
		$this->operand2 = str_replace(' ', '', $cmdlets[2] ?? '');
		$this->operand2 = (int)str_replace('R', '', $cmdlets[2] ?? '');
		foreach (COMMANDS::cases() as $command) {
			if($command->name == $cmdlets[0]) {
				return $command;
			}
		}
		throw new CommandException('Command not found' . $cmdlets[0]);
	}

}
