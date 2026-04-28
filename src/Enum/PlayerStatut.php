<?php

namespace App\Enum;

enum PlayerStatut: string
    {
        case Injuried = 'injuried';
        case Absent = 'absent';
        case Suspended = 'suspended';
    }