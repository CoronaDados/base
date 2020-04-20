<?php

namespace App\Helpers;

use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;

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

    public static function formatSymptoms($symptoms): array
    {
        $allSymptoms = [
            'febre' => 'Febre',
            'tosse-seca' => 'Tosse seca',
            'cansaco' => 'Cansaço',
            'dor-corpo' => 'Dor no corpo',
            'dor-garganta' => 'Dor de garganta',
            'congestao-nasal' => 'Congestão nasal',
            'corrimento-nasal' => 'Corrimento Nasal',
            'diarreia' => 'Diarreia',
            'dificuldade-respirar' => 'Dificuldade para respirar',
            'sem-paladar' => 'Sem paladar'
        ];

        $symptoms = (array) json_decode($symptoms);
        unset($symptoms['person_id']);

        $allSymptomsFiltered = array_values(
            array_filter(
                $allSymptoms,
                function ($key) use ($symptoms) {
                    return array_key_exists($key, $symptoms);
                },
                ARRAY_FILTER_USE_KEY
            )
        );

        $obs = $symptoms['obs'];

        return [$allSymptomsFiltered, $obs];
    }

    public static function formatDateFromDB($date): string
    {
        return Carbon::parse($date)->format('d/m/Y \à\s H:i:s');
    }

    public static function getPersonCode($id)
    {
        return Hashids::encode($id);
    }

    public static function getPercentValueFromTotal($value, $total)
    {
        if (!$value) return 0;

        return $value / $total * 100;
    }

    public static function getPercentFormatted($value)
    {
        return number_format($value, 2, ',', '.');
    }
}
