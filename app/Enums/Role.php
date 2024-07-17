<?php

declare(strict_types=1);

namespace App\Enums;

enum Role: string
{
    case Parent = 'parent';
    case Child = 'child';
    case Tutor = 'tutor';
}
