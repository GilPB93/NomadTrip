<?php

namespace App\Enum;

enum AccountStatus: string {
    case ACTIVE = 'Actif';
    case INACTIVE = 'Inactif';
}