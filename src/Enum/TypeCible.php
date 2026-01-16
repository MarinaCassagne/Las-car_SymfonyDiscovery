<?php

namespace App\Enum;

enum TypeCible: string 
{
    case Avis = 'Avis';
    case Trajet = 'Trajet';
    case Utilisateur = 'Utilisateur';
}