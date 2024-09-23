#!/usr/bin/env php
<?php
require __DIR__ . '/loader.php';
$numbers = [6, 10, 50, 90, 40, 60, 70];
$memory = new Memory($numbers);

$processor = new Processor($memory);
$program = [];
$program[] = (new Translator('mov R4, R5'))->processCommand();
$program[] = (new Translator('incr R5'))->processCommand();
$program[] = (new Translator('mov R0, R5'))->processCommand();
$program[] = (new Translator('incr R5'))->processCommand();
$program[] = (new Translator('mov R1, R5'))->processCommand();
$program[] = (new Translator('cmp R1, R0'))->processCommand();
$program[] = (new Translator('jmp 8, R1'))->processCommand();
$program[] = (new Translator('mov R0, R5'))->processCommand();
$program[] = (new Translator('cpy R2, R4'))->processCommand();
$program[] = (new Translator('cmp R4, R5'))->processCommand();
$program[] = (new Translator('jmp 12, R4'))->processCommand();
$program[] = (new Translator('frcjmp 13'))->processCommand();
$program[] = (new Translator('abrt'))->processCommand();
$program[] = (new Translator('cpy R4, R2'))->processCommand();
$program[] = (new Translator('incr R5'))->processCommand();
$program[] = (new Translator('frcjmp 4'))->processCommand();

$processor->loadProgram($program);
$processor->execute();

echo "Maximum value found: " . $processor->getRegisterValue(0)->read() . "\n";

