<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


/**
 * @method static static ADMINISTRATIVO()
 * @method static static FINANCEIRO()
 * @method static static OPERACIONAL()
 * @method static static COMERCIAL()
 * @method static static MEDICO()
 * @method static static DIRETORIA()
 */
final class SectorType extends Enum
{
    const ADMINISTRATIVO =  'Administrativo';
    const FINANCEIRO =  'Financeiro';
    const OPERACIONAL =  'Operacional';
    const COMERCIAL =  'Comercial';
    const MEDICO =  'Médico';
    const DIRETORIA =  'Diretoria';
}
