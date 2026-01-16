<?php 

namespace App\Enum;

enum StatutPaiement: string {
    case En_Attente = 'En attente';
    case Succes = 'Succés';
    case Echec = 'Échec';
    case Refuse = 'Refusé';
}