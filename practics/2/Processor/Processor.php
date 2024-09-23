<?php

class Processor {
    private $registers = [];
    private $memory;
    private $programCounter = 0;

    public function __construct($memory) {
        $this->memory = $memory;
        $this->registers = [
            new Register(), // Для хранения максимума
            new Register(), // Для хранения текущего значения
            new Register(), // Вспомогательный регистр
            new Register(),  // Для хранения результата сравнения
            new Register(),  // Для хранения длины массива
            new Register(),  // Для хранения текущего итератора gпроцессора
        ];
    }

    public function loadProgram($program) {
        $this->program = $program;
    }

    public function execute() {
        while ($this->programCounter != -1) {
            $command = $this->program[$this->programCounter];
            $this->executeCommand($command);
            $this->programCounter++;
        }
    }

    public function getRegisterValue($index) {
        return $this->registers[$index];
    }

    private function executeCommand($command) {
        $opCode = $this->decodeCommand($command);

        switch ($opCode[0]) {
            case 0: // Присвоение
                $this->registers[$opCode[1]]->write($this->memory->read($this->registers[$opCode[2]]->read()));
                break;
            case 1: // Разница
                $result = $this->registers[$opCode[1]]->read() - $this->registers[$opCode[2]]->read();
                $this->registers[$opCode[1]]->write($result);
                break;
            case 2: // JUMP TO по условию
                if ($this->registers[$opCode[2]]->read() < 0) {
                    $this->programCounter = $opCode[1] - 1;
                }
                break;
            case 3: // Инкремент
                $value = $this->registers[$opCode[1]]->read();
                $this->registers[$opCode[1]]->write(++$value);
                break;
            case 4: // Abort
                $this->programCounter = -2;
                break;
            case 5: // Jump TO без условия
                $this->programCounter = $opCode[1] - 1;
                break;
            case 6: // Запись из регистра в регистр
                $this->registers[$opCode[1]]->write($this->registers[$opCode[2]]->read());
                break;
            default:
                throw new Exception("Unknown command: $opCode");
        }

        $this->displayState($command);
    }

    private function decodeCommand($command) {
        $byte1 = $command & 0xFF; // Маска младшего байта (0xFF - это 255 в десятичной)
        $byte2 = ($command >> 8) & 0xFF; // Сдвиг на 8 бит вправо и маска на 1 байт
        $byte3 = ($command >> 16) & 0xFF; // Сдвиг на 16 бит вправо и маска на 1 байт
        return array_reverse([$byte1, $byte2, $byte3]);
    }

    private function displayState($command) {
        echo "Command: " . json_encode($command) . "\n";
        echo "Registers: \n";
        foreach ($this->registers as $name => $register) {
            echo "$name: " . $register->read() . "\n";
        }
        echo "Memory: " . json_encode($this->memory->getData()) . "\n";
        echo "ProgramCounter: " . $this->programCounter . "\n";
        echo "-------------------------------------\n";
    }
}
