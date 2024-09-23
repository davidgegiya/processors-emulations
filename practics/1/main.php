#!/usr/bin/env php
<?php
require __DIR__ . '/Memory.php';
require __DIR__ . '/Processor.php';
require __DIR__ . '/Register.php';
$numbers = [6, 10, 5, 90, 40, 600, 70];
$memory = new Memory($numbers);
$processor = new Processor($memory);

$program = [];

$program[] = encode(0, 4, 5); //0 Запись размера массива в 4 регистр (5 - указатель на индекс памяти)
$program[] = encode(3, 5);         //1 Инкремент 5 регистра
$program[] = encode(0, 0, 5); //2 Запись числа в 0 регистр
$program[] = encode(3, 5);         //3 Инкремент 5 регистра
$program[] = encode(0, 1, 5); //4 Запись числа в 1 регистр
$program[] = encode(1, 1, 0); //5 Сравнение 1 и 0 регистра (результат пишется в 1 регистр)
$program[] = encode(2, 8, 1); //6 Если 1 регистр < 0, переход на 8 команду
$program[] = encode(0, 0, 5); //7 Запись в 0 регистр нового числа, если перехода не было
$program[] = encode(6, 2, 4); //8 Бекап 4 регистра во 2 регистр
$program[] = encode(1, 4, 5); //9 Сравнение 4 и 5 регистра (размер массива - текущее положение итератора памяти)
$program[] = encode(2, 12, 4);//10 Если в 4 регистре получилось < 0, то переходим на 12 команду
$program[] = encode(5, 13);        //11 Иначе форс переход на 13
$program[] = encode(4);                  //12 Завершение
$program[] = encode(6, 4, 2); //13 Восстановление размера массива в 4 регистр
$program[] = encode(3, 5);         //14 Инкремент 5 регистра
$program[] = encode(5, 4);         //15 Переход на новую итерацию

function encode($command, $arg0 = 0, $arg1 = 0)
{
    return ($command << 16) | ($arg0 << 8) | $arg1;
}

$processor->loadProgram($program);
$processor->execute();

echo "Maximum value found: " . $processor->getRegisterValue(0)->read() . "\n";

