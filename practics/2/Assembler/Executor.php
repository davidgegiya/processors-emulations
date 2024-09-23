<?php

class Executor {

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
		switch ($this->command) {
			case COMMANDS::mov:
				$res = $this->encode(0);
				break;
			case COMMANDS::cmp:
				$res = $this->encode(1);
				break;
            case COMMANDS::jmp:
                $res = $this->encode(2);
                break;
            case COMMANDS::incr:
                $res = $this->encode(3);
                break;
            case COMMANDS::abrt:
                $res = $this->encode(4);
                break;
            case COMMANDS::frcjmp:
                $res = $this->encode(5);
                break;
            case COMMANDS::cpy:
                $res = $this->encode(6);
                break;
		}
		return $res;
	}
    private function encode($command)
    {
        return ($command << 16) | ($this->operand1 << 8) | $this->operand2;
    }
}