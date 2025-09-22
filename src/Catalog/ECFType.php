<?php

namespace Angle\ECF\Catalog;

use RuntimeException;

abstract class ECFType
{
    const TAX_CREDIT_INVOICE        = '31';
    const CONSUMER_INVOICE          = '32';
    const DEBIT_NOTE                = '33';
    const CREDIT_NOTE               = '34';
    const PURCHASE_VOUCHER          = '41';
    const MINOR_EXPENSES_VOUCHER    = '43';
    const SPECIAL_REGIMES_VOUCHER   = '44';
    const GOVERNMENTAL_VOUCHER      = '45';
    const EXPORT_VOUCHER            = '46';
    const PAYMENTS_ABROAD_VOUCHER   = '47';

    private static $map = [
        self::TAX_CREDIT_INVOICE => [
            'name' => [
                'en' => 'Tax Credit Invoice',
                'es' => 'Factura de Crédito Fiscal Electrónica',
            ],
        ],
        self::CONSUMER_INVOICE => [
            'name' => [
                'en' => 'Consumer Invoice',
                'es' => 'Factura de Consumo Electrónica',
            ],
        ],
        self::DEBIT_NOTE => [
            'name' => [
                'en' => 'Debit Note',
                'es' => 'Nota de Débito Electrónica',
            ],
        ],
        self::CREDIT_NOTE => [
            'name' => [
                'en' => 'Credit Note',
                'es' => 'Nota de Crédito Electrónica',
            ],
        ],
        self::PURCHASE_VOUCHER => [
            'name' => [
                'en' => 'Purchase Voucher',
                'es' => 'Comprobante de Compras',
            ],
        ],
        self::MINOR_EXPENSES_VOUCHER => [
            'name' => [
                'en' => 'Minor Expenses Voucher',
                'es' => 'Comprobante para Gastos Menores',
            ],
        ],
        self::SPECIAL_REGIMES_VOUCHER => [
            'name' => [
                'en' => 'Special Regimes Voucher',
                'es' => 'Comprobante para Regímenes Especiales',
            ],
        ],
        self::GOVERNMENTAL_VOUCHER => [
            'name' => [
                'en' => 'Governmental Voucher',
                'es' => 'Comprobante Gubernamental',
            ],
        ],
        self::EXPORT_VOUCHER => [
            'name' => [
                'en' => 'Export Voucher',
                'es' => 'Comprobante de Exportaciones',
            ],
        ],
        self::PAYMENTS_ABROAD_VOUCHER => [
            'name' => [
                'en' => 'Payments Abroad Voucher',
                'es' => 'Comprobante para Pagos al Exterior',
            ],
        ],
    ];

    public static function listForFormBuilder($lang='es'): array
    {
        $a = [];

        foreach (self::$map as $key => $props) {
            if (!array_key_exists($lang, $props['name'])) {
                throw new RuntimeException(sprintf('Language \'%s\' is not registered', $lang));
            }

            $label = $key . ' - ' . $props['name'][$lang];
            $a[$label] = $key;
        }

        return $a;
    }

    public static function getName($id, $lang='es'): ?string
    {
        if (!self::exists($id)) {
            return null;
        }

        if (!array_key_exists($lang, self::$map[$id]['name'])) {
            throw new RuntimeException(sprintf('Language \'%s\' is not registered', $lang));
        }

        return self::$map[$id]['name'][$lang];
    }

    public static function exists($id): bool
    {
        return array_key_exists($id, self::$map);
    }
}