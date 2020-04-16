<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


/**
 * @method static static NAO_REALIZADO()
 * @method static static EM_ANDAMENTO()
 * @method static static REALIZADO()
 */
final class StatusCovidTestType extends Enum
{
    const NAO_REALIZADO = 'Não realizado';
    const EM_ANDAMENTO = 'Em andamento';
    const REALIZADO = 'Realizado';
}
