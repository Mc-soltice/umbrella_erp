<?php

namespace App\Enums;

enum RolesEnum: string
{
    case ADMIN = 'admin';
    case responsable = 'responsable';
    case MANAGER = 'manager';
    case INTENDANT = 'intendant';
    case COMPTABLE = 'comptable';
    // Ajoutez d'autres rôles si besoin
}