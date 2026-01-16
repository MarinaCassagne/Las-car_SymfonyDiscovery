<?php

namespace App\Enum;

enum StatutValidTrajet: string 
{
    case En_Attente = 'En attente';    
    case Valide = 'Valide';
    case Refuse = 'Refusé';

}