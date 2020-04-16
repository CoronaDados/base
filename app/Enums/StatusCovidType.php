<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


/**
 * @method static static SUSPEITO()
 * @method static static NEGATIVO()
 * @method static static POSITIVO()
 * @method static static RECUPERADO()
 * @method static static OBITO()
 */
final class StatusCovidType extends Enum
{
    const SUSPEITO = 'Suspeito';
    const NEGATIVO = 'Negativo';
    const POSITIVO = 'Positivo';
    const RECUPERADO = 'Recuperado';
    const OBITO = 'Óbito';
}
