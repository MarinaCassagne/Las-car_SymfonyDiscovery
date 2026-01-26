<?php 

namespace App\Enum;

enum StatutReservation: string {
    case En_Attente = 'En attente';
    case Valide = 'Validé';
    case Refuse = 'Refusé';
    case Annule = 'Annulé';
}