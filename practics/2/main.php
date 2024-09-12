#!/usr/bin/env php
<?php
require __DIR__ . '/loader.php';
$memory = new Memory([10, 50, 30, 40]);

$processor = new Processor($memory);
$program = [
    ['opCode' => 0x000, 'args' => ['R0', 0]],  // R0 = memory[0] (текущий максимум)

    ['opCode' => 0x000, 'args' => ['R1', 1]],  // R1 = memory[1]
    ['opCode' => 0x001,  'args' => ['R1', 'R0']], // Сравниваем R1 с R0 (R3 = R1 - R0)
    ['opCode' => 0x002, 'args' => [6]], // Если R1 < R0, переход к следующему элементу
    ['opCode' => 0x000, 'args' => ['R0', 1]],  // Если R1 > R0, R0 = R1 (обновление максимума)

    ['opCode' => 0x000, 'args' => ['R1', 2]],  // Аналогично для memory[2]
    ['opCode' => 0x001,  'args' => ['R1', 'R0']],
    ['opCode' => 0x002, 'args' => [10]],
    ['opCode' => 0x000, 'args' => ['R0', 2]],

    ['opCode' => 0x000, 'args' => ['R1', 3]],  // Аналогично для memory[3]
    ['opCode' => 0x001,  'args' => ['R1', 'R0']],
    ['opCode' => 0x002, 'args' => [13]],
    ['opCode' => 0x000, 'args' => ['R0', 3]],
];

$processor->loadProgram($program);
$processor->execute();

echo "Maximum value found: " . $processor->getRegisterValue('R0')->read() . "\n";

