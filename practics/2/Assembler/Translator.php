<?php

class Translator {
    private $operand1;
    private $operand2;
    private $command_string;
    private static $labels = [];
    private static $currentLine = 0;

    public function __construct($command_string) {
        $this->command_string = $command_string;
    }

    public function processCommand($labels = true) {
        // Если это метка, сохраняем ее в массив
        if (strpos($this->command_string, ':') !== false && $labels) {
            preg_match('/(?<=:)\w+/', $this->command_string, $label);
            self::$labels[$label[0]] = self::$currentLine;
            self::$currentLine++;
            return null; // метки не кодируются как команды
        }

        // Парсинг обычной команды
        $executor = new Executor($this->parseCommandType(), $this->operand1, $this->operand2);
        self::$currentLine++;
        return $executor->execute();
    }

    private function parseCommandType() {
        $cmdlets = explode(' ', $this->command_string);
        $this->operand1 = str_replace(',', '', $cmdlets[1] ?? '');
        $this->operand1 = (int)str_replace('R', '', $cmdlets[1] ?? '');
        $this->operand2 = str_replace(' ', '', $cmdlets[2] ?? '');
        $this->operand2 = (int)str_replace('R', '', $cmdlets[2] ?? '');

        // Проверка, является ли это командой с меткой
        if (isset(self::$labels[str_replace(',', '', $cmdlets[1])])) {
            $this->operand1 = self::$labels[str_replace(',', '', $cmdlets[1])];
        }

        foreach (COMMANDS::cases() as $command) {
            if($command->name == $cmdlets[0]) {
                return $command;
            }
        }

        throw new CommandException('Command not found: ' . $cmdlets[0]);
    }

    public static function getLabels() {
        return self::$labels;
    }
}