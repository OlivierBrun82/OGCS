<?php

namespace App\Enum;

enum PlayerStatut: string
    {
        case Valide = 'valide';
        case Blessé = 'injuried';
        case Absent = 'absent';
        case Suspendu = 'suspended';
    }