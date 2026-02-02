<?php

function parseFloat(array $data, mixed $value, bool $required, ?string &$error): ?string
    {
        // Vérifie si la valeur est vide
        if ($value === null || $value === '') {
            if ($required) {
            $error = key ($data) . ' is required.';
            }
            return null;
        }

        // Vérifie si c'est un nombre
        if (!is_numeric($value)) {
            $error = key ($data) . ' must be numeric.';
            return $error;
            // return null;
        }

        // Convertit en float et vérifie que c'est positif
        $price = (float) $value;
        if ($price < 0) {
            $error = key ($data) . ' must be positive.';
            return $error;
            // return null;
        }

    }


$data = array (
    "fruits"  => 3.4,
    "numbers" => "orange2",
    "holes"   => ""
);

$value = $data['numbers'];

var_dump($value);
var_dump(parseFloat($data, $value, true,$error)) ?? $error;

