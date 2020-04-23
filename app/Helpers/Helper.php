<?php

namespace App\Helpers;

use App\Enums\SymptomsType;
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
        if (!$total) $total = 1;

        return $value / $total * 100;
    }

    public static function getPercentFormatted($value)
    {
        return number_format($value, 2, ',', '.');
    }

    public static function getPercentValueAndFormat($value, $total)
    {
        return self::getPercentFormatted(self::getPercentValueFromTotal($value, $total));
    }

    public static function getSymptomsDescriptionByValues($symptoms)
    {
        $symptomsDescription = [];

        foreach ($symptoms as $symptom) {
            $symptomsDescription[] = SymptomsType::getDescription($symptom);
        }

        return $symptomsDescription;
    }

    public static function formatPhone($number) {
        if(!isset($number)) {
            return 'Sem número cadastrado';
        }

        $formatedPhone = preg_replace('/[^0-9]/', '', $number);

        $matches = [];

        preg_match('/^([0-9]{2})([0-9]{4,5})([0-9]{4})$/', $formatedPhone, $matches);
        if ($matches) {
            return '('.$matches[1].') '.$matches[2].'-'.$matches[3];
        }

        return $number; // return number without format
    }
}
