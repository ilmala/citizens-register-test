<?php

namespace App\Enums;

enum Role: string
{
    case Parent = 'parent';
    case Child = 'child';
    case Guardian = 'guardian';
}
