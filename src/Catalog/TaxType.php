<?php

namespace Angle\ECF\Catalog;

use Angle\ECF\Catalog\TaxFactorType;

abstract class TaxType
{
    const ITBIS_18 = 'ITBIS1';
    const ITBIS_16 = 'ITBIS2';
    const ITBIS_0 = 'ITBIS3';
    const ISR_WITHHOLDING = 'ISR';

    private static $map = [
        self::ITBIS_18 => [
            'name' => 'ITBIS Tasa 1 (18%)',
            'shortName' => 'ITBIS 18%',
            'description' => 'Impuesto sobre la Transferencia de Bienes Industrializados y Servicios - Tasa 1 (18%)',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '18',
        ],
        self::ITBIS_16 => [
            'name' => 'ITBIS Tasa 2 (16%)',
            'shortName' => 'ITBIS 16%',
            'description' => 'Impuesto sobre la Transferencia de Bienes Industrializados y Servicios - Tasa 2 (16%)',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '16',
        ],
        self::ITBIS_0 => [
            'name' => 'ITBIS Tasa 3 (0%)',
            'shortName' => 'ITBIS 0%',
            'description' => 'Impuesto sobre la Transferencia de Bienes Industrializados y Servicios - Tasa 3 (0%)',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '0',
        ],
        self::ISR_WITHHOLDING => [
            'name' => 'Retención Impuesto Sobre la Renta',
            'shortName' => 'Retención ISR',
            'description' => 'Monto del Impuesto Sobre la Renta correspondiente a la retención realizada de la prestación o locación de servicios.',
            'taxFactorType' => TaxFactorType::RATE, // The rate is variable depending on the service/good
            'rate' => null, // Rate is not fixed
        ],
    ];

    public static function listForFormBuilder($lang = 'es'): array
    {
        $a = [];

        foreach (self::$map as $key => $props) {
            $label = $key . ' - ' . $props['name'];
            $a[$label] = $key;
        }

        return $a;
    }

    public static function getName($id, $lang = 'es'): ?string
    {
        if (!self::exists($id)) {
            return null;
        }

        return self::$map[$id]['name'];
    }

    public static function exists($id): bool
    {
        return array_key_exists($id, self::$map);
    }
}