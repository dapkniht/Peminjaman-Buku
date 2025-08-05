<?php

namespace App\Enums;

enum LoanStatus: string
{
    case Borrowed = 'borrowed';
    case Returned = 'returned';
    case Late = 'late';
}
