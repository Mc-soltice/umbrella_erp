<?php

namespace App\Enums;

enum AttendanceStatusEnum: string
{
    case WORKED = 'WORKED';
    case REST = 'REST';
    case ABSENT = 'ABSENT';
    case REPLACEMENT = 'REPLACEMENT';
}
