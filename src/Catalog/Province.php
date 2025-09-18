<?php

namespace Angle\ECF\Catalog;


abstract class Province
{
    const DISTRITO_NACIONAL = '010000';
    const AZUA = '020000';
    const BAHORUCO = '030000';
    const BARAHONA = '040000';
    const DAJABON = '050000';
    const DUARTE = '060000';
    const ELIAS_PINA = '070000';
    const EL_SEIBO = '080000';
    const ESPAILLAT = '090000';
    const INDEPENDENCIA = '100000';
    const LA_ALTAGRACIA = '110000';
    const LA_ROMANA = '120000';
    const LA_VEGA = '130000';
    const MARIA_TRINIDAD_SANCHEZ = '140000';
    const MONTE_CRISTI = '150000';
    const PEDERNALES = '160000';
    const PERAVIA = '170000';
    const PUERTO_PLATA = '180000';
    const HERMANAS_MIRABAL = '190000';
    const SAMANA = '200000';
    const SAN_CRISTOBAL = '210000';
    const SAN_JUAN = '220000';
    const SAN_PEDRO_DE_MACORIS = '230000';
    const SANCHEZ_RAMIREZ = '240000';
    const SANTIAGO = '250000';
    const SANTIAGO_RODRIGUEZ = '260000';
    const VALVERDE = '270000';
    const MONSENOR_NOUEL = '280000';
    const MONTE_PLATA = '290000';
    const HATO_MAYOR = '300000';
    const SAN_JOSE_DE_OCOA = '310000';
    const SANTO_DOMINGO = '320000';

    private static $map = [
        self::DISTRITO_NACIONAL => 'DISTRITO NACIONAL',
        self::AZUA => 'PROVINCIA AZUA',
        self::BAHORUCO => 'PROVINCIA BAHORUCO',
        self::BARAHONA => 'PROVINCIA BARAHONA',
        self::DAJABON => 'PROVINCIA DAJABÓN',
        self::DUARTE => 'PROVINCIA DUARTE',
        self::ELIAS_PINA => 'PROVINCIA ELÍAS PIÑA',
        self::EL_SEIBO => 'PROVINCIA EL SEIBO',
        self::ESPAILLAT => 'PROVINCIA ESPAILLAT',
        self::INDEPENDENCIA => 'PROVINCIA INDEPENDENCIA',
        self::LA_ALTAGRACIA => 'PROVINCIA LA ALTAGRACIA',
        self::LA_ROMANA => 'PROVINCIA LA ROMANA',
        self::LA_VEGA => 'PROVINCIA LA VEGA',
        self::MARIA_TRINIDAD_SANCHEZ => 'PROVINCIA MARÍA TRINIDAD SÁNCHEZ',
        self::MONTE_CRISTI => 'PROVINCIA MONTE CRISTI',
        self::PEDERNALES => 'PROVINCIA PEDERNALES',
        self::PERAVIA => 'PROVINCIA PERAVIA',
        self::PUERTO_PLATA => 'PROVINCIA PUERTO PLATA',
        self::HERMANAS_MIRABAL => 'PROVINCIA HERMANAS MIRABAL',
        self::SAMANA => 'PROVINCIA SAMANÁ',
        self::SAN_CRISTOBAL => 'PROVINCIA SAN CRISTÓBAL',
        self::SAN_JUAN => 'PROVINCIA SAN JUAN',
        self::SAN_PEDRO_DE_MACORIS => 'PROVINCIA SAN PEDRO DE MACORÍS',
        self::SANCHEZ_RAMIREZ => 'PROVINCIA SÁNCHEZ RAMÍREZ',
        self::SANTIAGO => 'PROVINCIA SANTIAGO',
        self::SANTIAGO_RODRIGUEZ => 'PROVINCIA SANTIAGO RODRÍGUEZ',
        self::VALVERDE => 'PROVINCIA VALVERDE',
        self::MONSENOR_NOUEL => 'PROVINCIA MONSEÑOR NOUEL',
        self::MONTE_PLATA => 'PROVINCIA MONTE PLATA',
        self::HATO_MAYOR => 'PROVINCIA HATO MAYOR',
        self::SAN_JOSE_DE_OCOA => 'PROVINCIA SAN JOSÉ DE OCOA',
        self::SANTO_DOMINGO => 'PROVINCIA SANTO DOMINGO',
    ];


    public static function listForFormBuilder(): array
    {
        $list = [];
        foreach (self::$map as $code => $name) {
            $list[$name] = $code;
        }
        return $list;
    }


    public static function getName(string $code): ?string
    {
        return self::$map[$code] ?? null;
    }

    public static function exists(string $code): bool
    {
        return array_key_exists($code, self::$map);
    }
}