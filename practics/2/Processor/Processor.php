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
			$cmd1 = new Translator('add ' . $this->programCounter . ', ' . 1);
			$cmd1->processCommand($this->programCounter);
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
				$cmd1 = new Translator('sub ' . $this->registers[$args[0]]->read() . ', ' . $this->registers[$args[1]]->read());
				$cmd1->processCommand($result);
				$this->registers['R3']->write($result);
				break;
			case 0x002:
				$cmd1 = new Translator('cmp ' . $this->registers['R3']->read() . ', ' . 0);
				$cmd1->processCommand($result);
				if ($result == EQUALITY::lt) {
					$cmd2 = new Translator('sub ' . $args[0] . ', ' . 1);
					$cmd2->processCommand($this->programCounter);
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