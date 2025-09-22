<?php

namespace Angle\ECF\Catalog;

use RuntimeException;

abstract class UnitType
{
    const BARREL = '1';
    const BAG = '2';
    const CAN = '3';
    const PACKAGE_OF_GOODS = '4';
    const BOTTLE = '5';
    const BOX = '6';
    const PACKET = '7';
    const CENTIMETER = '8';
    const CYLINDER = '9';
    const SET = '10';
    const CONTAINER = '11';
    const DAY = '12';
    const DOZEN = '13';
    const BUNDLE = '14';
    const GALLON = '15';
    const GRADE = '16';
    const GRAM = '17';
    const BULK = '18';
    const HOUR = '19';
    const CRATE = '20';
    const KILOGRAM = '21';
    const KILOWATT_HOUR = '22';
    const POUND = '23';
    const LITER = '24';
    const LOT = '25';
    const METER = '26';
    const SQUARE_METER = '27';
    const CUBIC_METER = '28';
    const MILLION_BTU = '29';
    const MINUTE = '30';
    const PACK = '31';
    const PAIR = '32';
    const FOOT = '33';
    const PIECE = '34';
    const ROLL = '35';
    const ENVELOPE = '36';
    const SECOND = '37';
    const TANK = '38';
    const TON = '39';
    const TUBE = '40';
    const YARD = '41';
    const SQUARE_YARD = '42';
    const UNIT = '43';
    const ELEMENT = '44';
    const THOUSAND = '45';
    const SACK = '46';
    const TIN = '47';
    const DISPLAY = '48';
    const JUG = '49';
    const RATION = '50';
    const QUINTAL = '51';
    const GROSS_REGISTER_TONNAGE = '52';
    const SQUARE_FOOT = '53';
    const PASSENGER = '54';
    const INCH = '55';
    const BOAT_PARKING_AT_DOCK = '56';
    const TRAY = '57';
    const HECTARE = '58';
    const MILLILITER = '59';
    const MILLIGRAM = '60';
    const OUNCE = '61';
    const TROY_OUNCE = '62';

    private static $map = [
        self::BARREL => [
            'abbreviation' => 'BARR',
            'name' => [
                'en' => 'Barrel',
                'es' => 'Barril',
            ],
        ],
        self::BAG => [
            'abbreviation' => 'BOL',
            'name' => [
                'en' => 'Bag',
                'es' => 'Bolsa',
            ],
        ],
        self::CAN => [
            'abbreviation' => 'BOT',
            'name' => [
                'en' => 'Can',
                'es' => 'Bote',
            ],
        ],
        self::PACKAGE_OF_GOODS => [
            'abbreviation' => 'BULTO',
            'name' => [
                'en' => 'Package of goods',
                'es' => 'Bultos',
            ],
        ],
        self::BOTTLE => [
            'abbreviation' => 'BOTELLA',
            'name' => [
                'en' => 'Bottle',
                'es' => 'Botella',
            ],
        ],
        self::BOX => [
            'abbreviation' => 'CAJ',
            'name' => [
                'en' => 'Box',
                'es' => 'Caja/Cajón',
            ],
        ],
        self::PACKET => [
            'abbreviation' => 'CAJETILLA',
            'name' => [
                'en' => 'Packet',
                'es' => 'Cajetilla',
            ],
        ],
        self::CENTIMETER => [
            'abbreviation' => 'CM',
            'name' => [
                'en' => 'Centimeter',
                'es' => 'Centímetro',
            ],
        ],
        self::CYLINDER => [
            'abbreviation' => 'CIL',
            'name' => [
                'en' => 'Cylinder',
                'es' => 'Cilindro',
            ],
        ],
        self::SET => [
            'abbreviation' => 'CONJ',
            'name' => [
                'en' => 'Set',
                'es' => 'Conjunto',
            ],
        ],
        self::CONTAINER => [
            'abbreviation' => 'CONT',
            'name' => [
                'en' => 'Container',
                'es' => 'Contenedor',
            ],
        ],
        self::DAY => [
            'abbreviation' => 'DÍA',
            'name' => [
                'en' => 'Day',
                'es' => 'Día',
            ],
        ],
        self::DOZEN => [
            'abbreviation' => 'DOC',
            'name' => [
                'en' => 'Dozen',
                'es' => 'Docena',
            ],
        ],
        self::BUNDLE => [
            'abbreviation' => 'FARD',
            'name' => [
                'en' => 'Bundle',
                'es' => 'Fardo',
            ],
        ],
        self::GALLON => [
            'abbreviation' => 'GL',
            'name' => [
                'en' => 'Gallon',
                'es' => 'Galones',
            ],
        ],
        self::GRADE => [
            'abbreviation' => 'GRAD',
            'name' => [
                'en' => 'Grade',
                'es' => 'Grado',
            ],
        ],
        self::GRAM => [
            'abbreviation' => 'GR',
            'name' => [
                'en' => 'Gram',
                'es' => 'Gramo',
            ],
        ],
        self::BULK => [
            'abbreviation' => 'GRAN',
            'name' => [
                'en' => 'Bulk',
                'es' => 'Granel',
            ],
        ],
        self::HOUR => [
            'abbreviation' => 'HOR',
            'name' => [
                'en' => 'Hour',
                'es' => 'Hora',
            ],
        ],
        self::CRATE => [
            'abbreviation' => 'HUAC',
            'name' => [
                'en' => 'Crate',
                'es' => 'Huacal',
            ],
        ],
        self::KILOGRAM => [
            'abbreviation' => 'KG',
            'name' => [
                'en' => 'Kilogram',
                'es' => 'Kilogramo',
            ],
        ],
        self::KILOWATT_HOUR => [
            'abbreviation' => 'kWh',
            'name' => [
                'en' => 'Kilowatt Hour',
                'es' => 'Kilovatio Hora',
            ],
        ],
        self::POUND => [
            'abbreviation' => 'LB',
            'name' => [
                'en' => 'Pound',
                'es' => 'Libra',
            ],
        ],
        self::LITER => [
            'abbreviation' => 'LITRO',
            'name' => [
                'en' => 'Liter',
                'es' => 'Litro',
            ],
        ],
        self::LOT => [
            'abbreviation' => 'LOT',
            'name' => [
                'en' => 'Lot',
                'es' => 'Lote',
            ],
        ],
        self::METER => [
            'abbreviation' => 'M',
            'name' => [
                'en' => 'Meter',
                'es' => 'Metro',
            ],
        ],
        self::SQUARE_METER => [
            'abbreviation' => 'M2',
            'name' => [
                'en' => 'Square Meter',
                'es' => 'Metro Cuadrado',
            ],
        ],
        self::CUBIC_METER => [
            'abbreviation' => 'M3',
            'name' => [
                'en' => 'Cubic Meter',
                'es' => 'Metro Cúbico',
            ],
        ],
        self::MILLION_BTU => [
            'abbreviation' => 'MMBTU',
            'name' => [
                'en' => 'Million BTU',
                'es' => 'Millones de Unidades Térmicas',
            ],
        ],
        self::MINUTE => [
            'abbreviation' => 'MIN',
            'name' => [
                'en' => 'Minute',
                'es' => 'Minuto',
            ],
        ],
        self::PACK => [
            'abbreviation' => 'PAQ',
            'name' => [
                'en' => 'Pack',
                'es' => 'Paquete',
            ],
        ],
        self::PAIR => [
            'abbreviation' => 'PAR',
            'name' => [
                'en' => 'Pair',
                'es' => 'Par',
            ],
        ],
        self::FOOT => [
            'abbreviation' => 'PIE',
            'name' => [
                'en' => 'Foot',
                'es' => 'Pie',
            ],
        ],
        self::PIECE => [
            'abbreviation' => 'PZA',
            'name' => [
                'en' => 'Piece',
                'es' => 'Pieza',
            ],
        ],
        self::ROLL => [
            'abbreviation' => 'ROL',
            'name' => [
                'en' => 'Roll',
                'es' => 'Rollo',
            ],
        ],
        self::ENVELOPE => [
            'abbreviation' => 'SOBR',
            'name' => [
                'en' => 'Envelope',
                'es' => 'Sobre',
            ],
        ],
        self::SECOND => [
            'abbreviation' => 'SEG',
            'name' => [
                'en' => 'Second',
                'es' => 'Segundo',
            ],
        ],
        self::TANK => [
            'abbreviation' => 'TANQUE',
            'name' => [
                'en' => 'Tank',
                'es' => 'Tanque',
            ],
        ],
        self::TON => [
            'abbreviation' => 'TONE',
            'name' => [
                'en' => 'Ton',
                'es' => 'Tonelada',
            ],
        ],
        self::TUBE => [
            'abbreviation' => 'TUB',
            'name' => [
                'en' => 'Tube',
                'es' => 'Tubo',
            ],
        ],
        self::YARD => [
            'abbreviation' => 'YD',
            'name' => [
                'en' => 'Yard',
                'es' => 'Yarda',
            ],
        ],
        self::SQUARE_YARD => [
            'abbreviation' => 'YD2',
            'name' => [
                'en' => 'Square Yard',
                'es' => 'Yarda cuadrada',
            ],
        ],
        self::UNIT => [
            'abbreviation' => 'UND',
            'name' => [
                'en' => 'Unit',
                'es' => 'Unidad',
            ],
        ],
        self::ELEMENT => [
            'abbreviation' => 'EA',
            'name' => [
                'en' => 'Element',
                'es' => 'Elemento',
            ],
        ],
        self::THOUSAND => [
            'abbreviation' => 'MILLAR',
            'name' => [
                'en' => 'Thousand',
                'es' => 'Millar',
            ],
        ],
        self::SACK => [
            'abbreviation' => 'SAC',
            'name' => [
                'en' => 'Sack',
                'es' => 'Saco',
            ],
        ],
        self::TIN => [
            'abbreviation' => 'LAT',
            'name' => [
                'en' => 'Tin',
                'es' => 'Lata',
            ],
        ],
        self::DISPLAY => [
            'abbreviation' => 'DIS',
            'name' => [
                'en' => 'Display',
                'es' => 'Display',
            ],
        ],
        self::JUG => [
            'abbreviation' => 'BID',
            'name' => [
                'en' => 'Jug',
                'es' => 'Bidón',
            ],
        ],
        self::RATION => [
            'abbreviation' => 'RAC',
            'name' => [
                'en' => 'Ration',
                'es' => 'Ración',
            ],
        ],
        self::QUINTAL => [
            'abbreviation' => 'Q',
            'name' => [
                'en' => 'Quintal',
                'es' => 'Quintal',
            ],
        ],
        self::GROSS_REGISTER_TONNAGE => [
            'abbreviation' => 'GRT',
            'name' => [
                'en' => 'Gross Register Tonnage',
                'es' => 'Toneladas de registro bruto',
            ],
        ],
        self::SQUARE_FOOT => [
            'abbreviation' => 'P2',
            'name' => [
                'en' => 'Square Foot',
                'es' => 'Pie cuadrado',
            ],
        ],
        self::PASSENGER => [
            'abbreviation' => 'PAX',
            'name' => [
                'en' => 'Passenger',
                'es' => 'Pasajero',
            ],
        ],
        self::INCH => [
            'abbreviation' => 'PULG',
            'name' => [
                'en' => 'Inch',
                'es' => 'Pulgadas',
            ],
        ],
        self::BOAT_PARKING_AT_DOCK => [
            'abbreviation' => 'STAY',
            'name' => [
                'en' => 'Boat Parking at Dock',
                'es' => 'Parqueo barcos en muelle',
            ],
        ],
        self::TRAY => [
            'abbreviation' => 'BDJ',
            'name' => [
                'en' => 'Tray',
                'es' => 'Bandeja',
            ],
        ],
        self::HECTARE => [
            'abbreviation' => 'HA',
            'name' => [
                'en' => 'Hectare',
                'es' => 'Hectárea',
            ],
        ],
        self::MILLILITER => [
            'abbreviation' => 'ML',
            'name' => [
                'en' => 'Milliliter',
                'es' => 'Mililitro',
            ],
        ],
        self::MILLIGRAM => [
            'abbreviation' => 'MG',
            'name' => [
                'en' => 'Milligram',
                'es' => 'Miligramo',
            ],
        ],
        self::OUNCE => [
            'abbreviation' => 'OZ',
            'name' => [
                'en' => 'Ounce',
                'es' => 'Onzas',
            ],
        ],
        self::TROY_OUNCE => [
            'abbreviation' => 'OZT',
            'name' => [
                'en' => 'Troy Ounce',
                'es' => 'Onzas Troy',
            ],
        ],
    ];

    public static function listForFormBuilder(string $lang = 'es'): array
    {
        $a = [];

        foreach (self::$map as $key => $props) {
            if (!array_key_exists($lang, $props['name'])) {
                throw new RuntimeException(sprintf('Language \'%s\' is not registered', $lang));
            }

            $label = $key . ' - ' . $props['abbreviation'] . ' (' . $props['name'][$lang] . ')';
            $a[$label] = $key;
        }

        return $a;
    }

    public static function getName(string $id, string $lang = 'es'): ?string
    {
        if (!self::exists($id)) {
            return null;
        }

        if (!array_key_exists($lang, self::$map[$id]['name'])) {
            throw new RuntimeException(sprintf('Language \'%s\' is not registered', $lang));
        }

        return self::$map[$id]['name'][$lang];
    }

    public static function getAbbreviation(string $id): ?string
    {
        if (!self::exists($id)) {
            return null;
        }

        return self::$map[$id]['abbreviation'];
    }

    public static function exists(string $id): bool
    {
        return array_key_exists($id, self::$map);
    }
}