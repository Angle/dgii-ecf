<?php

namespace Angle\ECF\Catalog;

use Angle\ECF\Catalog\TaxFactorType;
use RuntimeException;

abstract class AdditionalTaxType
{
    const LEGAL_TIP = '001';
    const CDT = '002'; //Contribución al Desarrollo de las Telecomunicaciones
    const ISC_INSURANCE_SERVICES = '003';
    const ISC_TELECOM_SERVICES = '004';
    const FIRST_VEHICLE_REGISTRATION = '005';
    const ISC_BEER_SPECIFIC = '006';
    const ISC_GRAPE_WINE_SPECIFIC = '007';
    const ISC_VERMOUTH_SPECIFIC = '008';
    const ISC_OTHER_FERMENTED_BEVERAGES_SPECIFIC = '009';
    const ISC_ETHYL_ALCOHOL_80_PLUS_SPECIFIC = '010';
    const ISC_ETHYL_ALCOHOL_UNDER_80_SPECIFIC = '011';
    const ISC_GRAPE_BRANDY_SPECIFIC = '012';
    const ISC_WHISKEY_SPECIFIC = '013';
    const ISC_RUM_SPECIFIC = '014';
    const ISC_GIN_SPECIFIC = '015';
    const ISC_VODKA_SPECIFIC = '016';
    const ISC_LIQUEURS_SPECIFIC = '017';
    const ISC_OTHER_BEVERAGES_AND_ALCOHOLS_SPECIFIC = '018';
    const ISC_TOBACCO_CIGARETTES_20_UNITS_SPECIFIC = '019';
    const ISC_OTHER_CIGARETTES_20_UNITS_SPECIFIC = '020';
    const ISC_TOBACCO_CIGARETTES_10_UNITS_SPECIFIC = '021';
    const ISC_OTHER_CIGARETTES_10_UNITS_SPECIFIC = '022';
    const ISC_BEER_AD_VALOREM = '023';
    const ISC_GRAPE_WINE_AD_VALOREM = '024';
    const ISC_VERMOUTH_AD_VALOREM = '025';
    const ISC_OTHER_FERMENTED_BEVERAGES_AD_VALOREM = '026';
    const ISC_ETHYL_ALCOHOL_80_PLUS_AD_VALOREM = '027';
    const ISC_ETHYL_ALCOHOL_UNDER_80_AD_VALOREM = '028';
    const ISC_GRAPE_BRANDY_AD_VALOREM = '029';
    const ISC_WHISKEY_AD_VALOREM = '030';
    const ISC_RUM_AD_VALOREM = '031';
    const ISC_GIN_AD_VALOREM = '032';
    const ISC_VODKA_AD_VALOREM = '033';
    const ISC_LIQUEURS_AD_VALOREM = '034';
    const ISC_OTHER_BEVERAGES_AND_ALCOHOLS_AD_VALOREM = '035';
    const ISC_TOBACCO_CIGARETTES_20_UNITS_AD_VALOREM = '036';
    const ISC_OTHER_CIGARETTES_20_UNITS_AD_VALOREM = '037';
    const ISC_TOBACCO_CIGARETTES_10_UNITS_AD_VALOREM = '038';
    const ISC_OTHER_CIGARETTES_10_UNITS_AD_VALOREM = '039';


    private static $map = [
        self::LEGAL_TIP => [
            'name' => 'Propina Legal',
            'shortName' => 'Propina Legal',
            'description' => 'Propina Legal',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '10',
        ],
        self::CDT => [
            'name' => 'Contribución al Desarrollo de las Telecomunicaciones',
            'shortName' => 'CDT',
            'description' => 'Contribución al Desarrollo de las Telecomunicaciones Ley 153-98 Art. 45',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '2',
        ],
        self::ISC_INSURANCE_SERVICES => [
            'name' => 'Impuesto Selectivo al Consumo',
            'shortName' => 'ISC',
            'description' => 'Servicios Seguros en general',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '16',
        ],
        self::ISC_TELECOM_SERVICES => [
            'name' => 'Impuesto Selectivo al Consumo',
            'shortName' => 'ISC',
            'description' => 'Servicios de Telecomunicaciones',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '10',
        ],
        self::FIRST_VEHICLE_REGISTRATION => [
            'name' => 'Impuesto sobre el Primer Registro de Vehículos (Primera Placa)',
            'shortName' => 'Primera Placa',
            'description' => 'Expedición de la primera placa',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '17',
        ],
        self::ISC_BEER_SPECIFIC => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa Específico)',
            'shortName' => 'ISC Específico',
            'description' => 'Cerveza',
            'taxFactorType' => TaxFactorType::FEE, // This is a fixed amount
            'rate' => '632.58',
        ],
        self::ISC_GRAPE_WINE_SPECIFIC => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa Específico)',
            'shortName' => 'ISC Específico',
            'description' => 'Vinos de uva',
            'taxFactorType' => TaxFactorType::FEE,
            'rate' => '632.58',
        ],
        self::ISC_VERMOUTH_SPECIFIC => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa Específico)',
            'shortName' => 'ISC Específico',
            'description' => 'Vermut y demás vinos de uvas frescas',
            'taxFactorType' => TaxFactorType::FEE,
            'rate' => '632.58',
        ],
        self::ISC_OTHER_FERMENTED_BEVERAGES_SPECIFIC => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa Específico)',
            'shortName' => 'ISC Específico',
            'description' => 'Demás bebidas fermentadas',
            'taxFactorType' => TaxFactorType::FEE,
            'rate' => '632.58',
        ],
        self::ISC_ETHYL_ALCOHOL_80_PLUS_SPECIFIC => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa Específico)',
            'shortName' => 'ISC Específico',
            'description' => 'Alcohol Etílico sin desnaturalizar (Mayor o igual a 80%)',
            'taxFactorType' => TaxFactorType::FEE,
            'rate' => '632.58',
        ],
        self::ISC_ETHYL_ALCOHOL_UNDER_80_SPECIFIC => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa Específico)',
            'shortName' => 'ISC Específico',
            'description' => 'Alcohol Etílico sin desnaturalizar (inferior a 80%)',
            'taxFactorType' => TaxFactorType::FEE,
            'rate' => '632.58',
        ],
        self::ISC_GRAPE_BRANDY_SPECIFIC => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa Específico)',
            'shortName' => 'ISC Específico',
            'description' => 'Aguardientes de uva',
            'taxFactorType' => TaxFactorType::FEE,
            'rate' => '632.58',
        ],
        self::ISC_WHISKEY_SPECIFIC => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa Específico)',
            'shortName' => 'ISC Específico',
            'description' => 'Whisky',
            'taxFactorType' => TaxFactorType::FEE,
            'rate' => '632.58',
        ],
        self::ISC_RUM_SPECIFIC => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa Específico)',
            'shortName' => 'ISC Específico',
            'description' => 'Ron y demás aguardientes de caña',
            'taxFactorType' => TaxFactorType::FEE,
            'rate' => '632.58',
        ],
        self::ISC_GIN_SPECIFIC => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa Específico)',
            'shortName' => 'ISC Específico',
            'description' => 'Gin y Ginebra',
            'taxFactorType' => TaxFactorType::FEE,
            'rate' => '632.58',
        ],
        self::ISC_VODKA_SPECIFIC => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa Específico)',
            'shortName' => 'ISC Específico',
            'description' => 'Vodka',
            'taxFactorType' => TaxFactorType::FEE,
            'rate' => '632.58',
        ],
        self::ISC_LIQUEURS_SPECIFIC => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa Específico)',
            'shortName' => 'ISC Específico',
            'description' => 'Licores',
            'taxFactorType' => TaxFactorType::FEE,
            'rate' => '632.58',
        ],
        self::ISC_OTHER_BEVERAGES_AND_ALCOHOLS_SPECIFIC => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa Específico)',
            'shortName' => 'ISC Específico',
            'description' => 'Los demás (Bebidas y Alcoholes)',
            'taxFactorType' => TaxFactorType::FEE,
            'rate' => '632.58',
        ],
        self::ISC_TOBACCO_CIGARETTES_20_UNITS_SPECIFIC => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa Específico)',
            'shortName' => 'ISC Específico',
            'description' => 'Cigarrillos que contengan tabaco cajetilla 20 unidades',
            'taxFactorType' => TaxFactorType::FEE,
            'rate' => '53.51',
        ],
        self::ISC_OTHER_CIGARETTES_20_UNITS_SPECIFIC => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa Específico)',
            'shortName' => 'ISC Específico',
            'description' => 'Los demás Cigarrillos que contengan 20 unidades',
            'taxFactorType' => TaxFactorType::FEE,
            'rate' => '53.51',
        ],
        self::ISC_TOBACCO_CIGARETTES_10_UNITS_SPECIFIC => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa Específico)',
            'shortName' => 'ISC Específico',
            'description' => 'Cigarrillos que contengan 10 unidades',
            'taxFactorType' => TaxFactorType::FEE,
            'rate' => '26.75',
        ],
        self::ISC_OTHER_CIGARETTES_10_UNITS_SPECIFIC => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa Específico)',
            'shortName' => 'ISC Específico',
            'description' => 'Los demás Cigarrillos que contengan 10 unidades',
            'taxFactorType' => TaxFactorType::FEE,
            'rate' => '26.75',
        ],
        self::ISC_BEER_AD_VALOREM => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa AdValorem)',
            'shortName' => 'ISC AdValorem',
            'description' => 'Cerveza',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '10',
        ],
        self::ISC_GRAPE_WINE_AD_VALOREM => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa AdValorem)',
            'shortName' => 'ISC AdValorem',
            'description' => 'Vinos de uva',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '10',
        ],
        self::ISC_VERMOUTH_AD_VALOREM => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa AdValorem)',
            'shortName' => 'ISC AdValorem',
            'description' => 'Vermut y demás vinos de uvas frescas',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '10',
        ],
        self::ISC_OTHER_FERMENTED_BEVERAGES_AD_VALOREM => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa AdValorem)',
            'shortName' => 'ISC AdValorem',
            'description' => 'Demás bebidas fermentadas',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '10',
        ],
        self::ISC_ETHYL_ALCOHOL_80_PLUS_AD_VALOREM => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa AdValorem)',
            'shortName' => 'ISC AdValorem',
            'description' => 'Alcohol Etílico sin desnaturalizar (Mayor o igual a 80%)',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '10',
        ],
        self::ISC_ETHYL_ALCOHOL_UNDER_80_AD_VALOREM => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa AdValorem)',
            'shortName' => 'ISC AdValorem',
            'description' => 'Alcohol Etílico sin desnaturalizar (inferior a 80%)',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '10',
        ],
        self::ISC_GRAPE_BRANDY_AD_VALOREM => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa AdValorem)',
            'shortName' => 'ISC AdValorem',
            'description' => 'Aguardientes de uva',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '10',
        ],
        self::ISC_WHISKEY_AD_VALOREM => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa AdValorem)',
            'shortName' => 'ISC AdValorem',
            'description' => 'Whisky',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '10',
        ],
        self::ISC_RUM_AD_VALOREM => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa AdValorem)',
            'shortName' => 'ISC AdValorem',
            'description' => 'Ron y demás aguardientes de caña',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '10',
        ],
        self::ISC_GIN_AD_VALOREM => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa AdValorem)',
            'shortName' => 'ISC AdValorem',
            'description' => 'Gin y Ginebra',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '10',
        ],
        self::ISC_VODKA_AD_VALOREM => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa AdValorem)',
            'shortName' => 'ISC AdValorem',
            'description' => 'Vodka',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '10',
        ],
        self::ISC_LIQUEURS_AD_VALOREM => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa AdValorem)',
            'shortName' => 'ISC AdValorem',
            'description' => 'Licores',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '10',
        ],
        self::ISC_OTHER_BEVERAGES_AND_ALCOHOLS_AD_VALOREM => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa AdValorem)',
            'shortName' => 'ISC AdValorem',
            'description' => 'Los demás (Bebidas y Alcoholes)',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '10',
        ],
        self::ISC_TOBACCO_CIGARETTES_20_UNITS_AD_VALOREM => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa AdValorem)',
            'shortName' => 'ISC AdValorem',
            'description' => 'Cigarrillos que contengan tabaco cajetilla 20 unidades',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '20',
        ],
        self::ISC_OTHER_CIGARETTES_20_UNITS_AD_VALOREM => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa AdValorem)',
            'shortName' => 'ISC AdValorem',
            'description' => 'Los demás Cigarrillos que contengan 20 unidades',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '20',
        ],
        self::ISC_TOBACCO_CIGARETTES_10_UNITS_AD_VALOREM => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa AdValorem)',
            'shortName' => 'ISC AdValorem',
            'description' => 'Cigarrillos que contengan 10 unidades',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '20',
        ],
        self::ISC_OTHER_CIGARETTES_10_UNITS_AD_VALOREM => [
            'name' => 'Impuesto Selectivo al Consumo (Tasa AdValorem)',
            'shortName' => 'ISC AdValorem',
            'description' => 'Los demás Cigarrillos que contengan 10 unidades',
            'taxFactorType' => TaxFactorType::RATE,
            'rate' => '20',
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