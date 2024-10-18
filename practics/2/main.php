#!/usr/bin/env php
<?php
require __DIR__ . '/loader.php';
$numbers = [6, 10, 50, 90, 40, 60, 70];
$memory = new Memory($numbers);

$processor = new Processor($memory);
$program = [];
$program[] = (new Translator('mov R4, R5'));
$program[] = (new Translator('incr R5'));
$program[] = (new Translator('mov R0, R5'));
$program[] = (new Translator('incr R5'));
$program[] = (new Translator('mov R1, R5 :start'));
$program[] = (new Translator('cmp R1, R0'));
$program[] = (new Translator('jmp max, R1'));
$program[] = (new Translator('mov R0, R5'));
$program[] = (new Translator('cpy R2, R4 :max'));
$program[] = (new Translator('cmp R4, R5'));
$program[] = (new Translator('jmp abort, R4'));
$program[] = (new Translator('frcjmp copy'));
$program[] = (new Translator('abrt :abort'));
$program[] = (new Translator('cpy R4, R2 :copy'));
$program[] = (new Translator('incr R5'));
$program[] = (new Translator('frcjmp start'));

foreach ($program as $pg) {
    $pg->processCommand();
}
foreach ($program as &$pg) {
    $pg = $pg->processCommand(false);
}

$processor->loadProgram($program);
$processor->execute();

echo "Maximum value found: " . $processor->getRegisterValue(0)->read() . "\n";

