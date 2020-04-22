<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static NAO()
 * @method static static GESTANTE()
 * @method static static ACIMA_60ANOS()
 * @method static static DIABETES()
 * @method static static PROBLEMAS_CARDIOVASCULARES()
 * @method static static PROBLEMAS_RESPIRATORIOS()
 * @method static static IMUNOSSUPRIMIDO()
 */
final class RiskGroupType extends Enum
{
    const GESTANTE = 'Gestante';
    const ACIMA_60ANOS = 'Acima de 60 anos';
    const DIABETES = 'Diabetes';
    const PROBLEMAS_CARDIOVASCULARES = 'Problemas Cardiovasculares';
    const PROBLEMAS_RESPIRATORIOS = 'Problemas Respiratórios';
    const IMUNOSSUPRIMIDO = 'Imunossuprimido';
}
