<?php

namespace App\Helpers;

use Carbon\Carbon;

class Helper
{
    public static function removePunctuation($string)
    {
        return preg_replace('/[^0-9]/', '', $string);
    }

    public static function getFirstAndLastName($name): string
    {
        $explodedName = explode(' ', $name);
        $maxLength = count($explodedName) - 1;
        $firstName = $explodedName[0];
        $lastName = $explodedName[$maxLength];

        return $firstName . ' ' . $lastName;
    }

    public static function formatStatus($status): array
    {
        $allSymptoms = [
            'febre' => 'Febre',
            'tosse-seca' => 'Tosse seca',
            'cansaco' => 'Cansaço',
            'dor-corpo' => 'Dor no corpo',
            'dor-garganta' => 'Dor de Garganta',
            'congestao-nasal' => 'Congestão Nasal',
            'diarreia' => 'Diarreia',
            'dificuldade-respirar' => 'Falta de ar/Dificuldade para respirar'
        ];

        $status = (array) json_decode($status);
        unset($status['person_id']);

        $allSymptomsFiltered = array_values(
            array_filter(
                $allSymptoms,
                function ($key) use ($status) {
                    return array_key_exists($key, $status);
                },
                ARRAY_FILTER_USE_KEY
            )
        );

        $obs = $status['obs'];

        return [$allSymptomsFiltered, $obs];
    }

    public static function formatDateFromDB($date): string
    {
        return Carbon::parse($date)->format('d/m/Y \à\s H:i:s');
    }
}
