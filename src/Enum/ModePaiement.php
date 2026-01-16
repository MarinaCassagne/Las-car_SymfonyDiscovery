<?php

namespace App\Enum;

enum ModePaiement: string 
{
    case Stripe = 'Stripe';
    case Solde = 'Solde';

}