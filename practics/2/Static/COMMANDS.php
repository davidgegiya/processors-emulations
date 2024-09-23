<?php

enum COMMANDS {
	case mov; // присвоение
	case cmp; // сравнение
    case jmp; // переход
    case incr; // инкремент
    case abrt; // завершение
    case frcjmp; // принудительный перехож
    case cpy; // копирование
}
