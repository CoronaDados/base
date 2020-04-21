<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SymptomsType extends Enum
{
    const FEBRE = 'febre';
    const TOSSE_SECA = 'tosse-seca';
    const CANSACO = 'cansaco';
    const DOR_CORPO = 'dor-corpo';
    const DOR_GARGANTA = 'dor-garganta';
    const CONGESTAO_NASAL = 'congestao-nasal';
    const CORIZA = 'coriza';
    const DIARREIA = 'diarreia';
    const SEM_PALADAR = 'sem-paladar';
    const DIFICULDADE_RESPIRAR = 'dificuldade-respirar';

    public static function getDescription($value): string
    {
        if ($value === self::FEBRE) {
            return 'Febre';
        }
        if ($value === self::TOSSE_SECA) {
            return 'Tosse Seca';
        }
        if ($value === self::CANSACO) {
            return 'Cansaço';
        }
        if ($value === self::DOR_CORPO) {
            return 'Dor no corpo';
        }
        if ($value === self::DOR_GARGANTA) {
            return 'Dor de garganta';
        }
        if ($value === self::CONGESTAO_NASAL) {
            return 'Congestão nasal';
        }
        if ($value === self::CORIZA) {
            return 'Coriza';
        }
        if ($value === self::DIARREIA) {
            return 'Diarréia';
        }
        if ($value === self::SEM_PALADAR) {
            return 'Sem paladar';
        }
        if ($value === self::DIFICULDADE_RESPIRAR) {
            return 'Dificuldade para respirar';
        }
    }

    public static function getScore($value): string
    {
        if ($value === self::FEBRE) {
            return 2;
        }
        if ($value === self::TOSSE_SECA) {
            return 2;
        }
        if ($value === self::CANSACO) {
            return 2;
        }
        if ($value === self::DOR_CORPO) {
            return 1;
        }
        if ($value === self::DOR_GARGANTA) {
            return 1;
        }
        if ($value === self::CONGESTAO_NASAL) {
            return 1;
        }
        if ($value === self::CORIZA) {
            return 1;
        }
        if ($value === self::DIARREIA) {
            return 1;
        }
        if ($value === self::SEM_PALADAR) {
            return 2;
        }
        if ($value === self::DIFICULDADE_RESPIRAR) {
            return 3;
        }
    }

    public static function getValueById($value)
    {
        $value = (int) $value;
        
        if ($value === 1) {
            return self::FEBRE;
        }
        if ($value === 2) {
            return self::TOSSE_SECA;
        }
        if ($value === 3) {
            return self::CANSACO;
        }
        if ($value === 4) {
            return self::DOR_CORPO;
        }
        if ($value === 5) {
            return self::DOR_GARGANTA;
        }
        if ($value === 6) {
            return self::CONGESTAO_NASAL;
        }
        if ($value === 7) {
            return self::CORIZA;
        }
        if ($value === 8) {
            return self::DIARREIA;
        }
        if ($value === 9) {
            return self::SEM_PALADAR;
        }
        if ($value === 10) {
            return self::DIFICULDADE_RESPIRAR;
        }

        return null;
    }
}
