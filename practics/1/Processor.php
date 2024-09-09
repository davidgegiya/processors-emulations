<?php

class Processor {
	private $registers = [];
	private $memory;
	private $programCounter = 0;

	public function __construct($memory) {
		$this->memory = $memory;
		$this->registers = [
			'R0' => new Register(), // Для хранения максимума
			'R1' => new Register(), // Для хранения текущего значения
			'R2' => new Register(), // Вспомогательный регистр
			'R3' => new Register(),  // Для хранения результата сравнения
		];
	}

	public function loadProgram($program) {
		$this->program = $program;
	}

	public function execute() {
		while ($this->programCounter < count($this->program)) {
			$command = $this->program[$this->programCounter];
			$this->executeCommand($command);
			$this->programCounter++;
		}
	}

	public function getRegisterValue($index) {
		return $this->registers[$index];
	}

	private function executeCommand($command) {
		$opCode = $command['opCode'];
		$args = $command['args'];

		switch ($opCode) {
			case 0x000:
				$this->registers[$args[0]]->write($this->memory->read($args[1]));
				break;
			case 0x001:
				$result = $this->registers[$args[0]]->read() - $this->registers[$args[1]]->read();
				$this->registers['R3']->write($result);
				break;
			case 0x002:
				if ($this->registers['R3']->read() < 0) {
					$this->programCounter = $args[0] - 1;
				}
				break;
			default:
				throw new Exception("Unknown command: $opCode");
		}

		$this->displayState($command);
	}

	private function displayState($command) {
		echo "Command: " . json_encode($command) . "\n";
		echo "Registers: \n";
		foreach ($this->registers as $name => $register) {
			echo "$name: " . $register->read() . "\n";
		}
		echo "Memory: " . json_encode($this->memory->getData()) . "\n";
		echo "-------------------------------------\n";
	}
}