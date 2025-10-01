<?php

namespace Angle\ECF\Tests;

use Angle\ECF\Catalog\AdditionalTaxType;
use Angle\ECF\Node\ECF10\ECF10;
use Angle\ECF\Node\ECF10\Header;
use Angle\ECF\Node\ECF10\Header\DocId;
use Angle\ECF\Node\ECF10\Header\DocId\EcfType;
use Angle\ECF\Node\ECF10\Header\DocId\Encf;
use Angle\ECF\Node\ECF10\Header\DocId\IncomeType;
use Angle\ECF\Node\ECF10\Header\DocId\PaymentType as DocIdPaymentType;
use Angle\ECF\Node\ECF10\Header\DocId\SequenceExpirationDate;
use Angle\ECF\Node\ECF10\Header\Issuer;
use Angle\ECF\Node\ECF10\Header\Issuer\IssueDate;
use Angle\ECF\Node\ECF10\Header\Issuer\IssuerAddress;
use Angle\ECF\Node\ECF10\Header\Issuer\IssuerRNC;
use Angle\ECF\Node\ECF10\Header\Issuer\CompanyName as IssuerCompanyName;
use Angle\ECF\Node\ECF10\Header\Recipient;
use Angle\ECF\Node\ECF10\Header\Recipient\CompanyName as RecipientCompanyName;
use Angle\ECF\Node\ECF10\Header\Recipient\RecipientRNC;
use Angle\ECF\Node\ECF10\Header\Version;
use Angle\ECF\Node\ECF10\ItemDetails;
use Angle\ECF\Node\ECF10\ItemDetails\Item;
use Angle\ECF\Node\ECF10\ItemDetails\Item\AdditionalTaxTable;
use Angle\ECF\Node\ECF10\ItemDetails\Item\AdditionalTaxTable\AdditionalTax;
use Angle\ECF\Node\ECF10\ItemDetails\Item\AdditionalTaxTable\AdditionalTax\TaxType;
use Angle\ECF\Node\ECF10\ItemDetails\Item\AlcoholPercentage;
use Angle\ECF\Node\ECF10\ItemDetails\Item\BillingIndicator;
use Angle\ECF\Node\ECF10\ItemDetails\Item\GoodOrServiceIndicator;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ItemAmount;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ItemName;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ItemQuantity;
use Angle\ECF\Node\ECF10\ItemDetails\Item\LineNumber;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ReferenceQuantity;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ReferenceUnit;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ReferenceUnitPrice;
use Angle\ECF\Node\ECF10\ItemDetails\Item\SubquantityTable;
use Angle\ECF\Node\ECF10\ItemDetails\Item\SubquantityTable\SubquantityItem;
use Angle\ECF\Node\ECF10\ItemDetails\Item\SubquantityTable\SubquantityItem\Subquantity;
use Angle\ECF\Node\ECF10\ItemDetails\Item\SubquantityTable\SubquantityItem\SubquantityCode;
use Angle\ECF\Node\ECF10\ItemDetails\Item\UnitOfMeasure;
use Angle\ECF\Node\ECF10\ItemDetails\Item\UnitPrice;
use PHPUnit\Framework\TestCase;

final class InvoiceTest extends TestCase
{
    public function testInvoiceCreation(): void
    {
        $pfxFile = __DIR__ . '/../test-data/test_certificate.pfx';
        $pfxPassword = __DIR__ . '/../test-data/pfx_password.txt';

        $data = [
            // Section A: Encabezado - All invoice identification and totals.
            Header::NODE_NAME => [
                // Version of the e-CF format. Mandatory.
                Version::NODE_NAME => ['value' => Version::VERSION_1_0],
                // Subsection: Document Identification.
                DocId::NODE_NAME => [
                    // Code for the e-CF type. '31' is for Factura de Crédito Fiscal Electrónica. Mandatory.
                    EcfType::NODE_NAME => ['value' => '31'],
                    // The e-NCF sequence number provided by DGII. Mandatory.
                    Encf::NODE_NAME => ['value' => 'E310000000001'],
                    // Expiration date of the e-NCF sequence. Mandatory.
                    SequenceExpirationDate::NODE_NAME => ['value' => '31-12-2026'],
                    // Type of income. '01' is for operational income. Mandatory.
                    IncomeType::NODE_NAME => ['value' => '01'],
                    // Payment type. '1' for cash (Contado), '2' for credit. Mandatory.
                    DocIdPaymentType::NODE_NAME => ['value' => '1'],
                ],
                // Subsection: Emitter (Seller) Information.
                Issuer::NODE_NAME => [
                    // RNC of the emitter. Mandatory.
                    IssuerRNC::NODE_NAME => ['value' => '101000001'],
                    // Legal name of the emitter. Mandatory.
                    IssuerCompanyName::NODE_NAME => ['value' => 'EMPRESA EMISORA SRL'],
                    // Address of the emitter. Mandatory.
                    IssuerAddress::NODE_NAME => ['value' => 'CALLE SOL, NO. 1, SECTOR LA FE'],
                    // Issue date of the e-CF. Mandatory.
                    IssueDate::NODE_NAME => ['value' => '17-09-2025'],
                ],
                // Subsection: Buyer Information.
                Recipient::NODE_NAME => [
                    // RNC of the buyer. Mandatory for type 31.
                    RecipientRNC::NODE_NAME => ['value' => '130000001'],
                    // Legal name of the buyer. Mandatory for type 31.
                    RecipientCompanyName::NODE_NAME => ['value' => 'EMPRESA COMPRADORA SRL'],
                ],
            ],
            // Section B: DetallesItems - Line items of the invoice.
            ItemDetails::NODE_NAME => [
                Item::NODE_NAME => [
                    // First Item
                    [
                        // Line number. [cite_start]Mandatory. [cite: 197]
                        LineNumber::NODE_NAME => ['value' => '1'],
                        // Tax indicator. '1' corresponds to ITBIS Tasa 1 (18%). [cite_start]Mandatory. [cite: 203]
                        BillingIndicator::NODE_NAME => ['value' => '1'],
                        // Item name/description. [cite_start]Mandatory. [cite: 210]
                        ItemName::NODE_NAME => ['value' => 'Producto de Prueba A'],
                        // Indicates if it's a good ('1') or service ('2'). [cite_start]Mandatory. [cite: 210]
                        GoodOrServiceIndicator::NODE_NAME => ['value' => '1'],
                        // Quantity of the item. [cite_start]Mandatory. [cite: 210]
                        ItemQuantity::NODE_NAME => ['value' => '2.00'],
                        // Unit price of the item. [cite_start]Mandatory. [cite: 226]
                        UnitPrice::NODE_NAME => ['value' => '100.00'],
                        // Total for this line (Cantidad * PrecioUnitario). [cite_start]Mandatory. [cite: 249]
                        ItemAmount::NODE_NAME => ['value' => '200.00'],
                    ],
                    // Second Item
                    [
                        LineNumber::NODE_NAME => ['value' => '2'],
                        BillingIndicator::NODE_NAME => ['value' => '1'],
                        ItemName::NODE_NAME => ['value' => 'Producto de Prueba B'],
                        GoodOrServiceIndicator::NODE_NAME => ['value' => '1'],
                        ItemQuantity::NODE_NAME => ['value' => '5.00'],
                        UnitPrice::NODE_NAME => ['value' => '50.00'],
                        ItemAmount::NODE_NAME => ['value' => '250.00'],
                    ],
                    [
                        LineNumber::NODE_NAME => ['value' => '3'],
                        BillingIndicator::NODE_NAME => ['value' => '1'],
                        ItemName::NODE_NAME => ['value' => 'Presidente Light 16/850 ML 22 Onz.'],
                        GoodOrServiceIndicator::NODE_NAME => ['value' => '1'],
                        ItemQuantity::NODE_NAME => ['value' => '1'],
                        UnitOfMeasure::NODE_NAME => ['value' => '31'],
                        ReferenceQuantity::NODE_NAME => ['value' => '16.00'],
                        ReferenceUnit::NODE_NAME => ['value' => '43'],
                        SubquantityTable::NODE_NAME => [
                            SubquantityItem::NODE_NAME =>
                            [
                                [
                                    Subquantity::NODE_NAME => ['value' => '.65'],
                                    SubquantityCode::NODE_NAME => ['value' => '24'],
                                ]
                            ]
                        ],
                        AlcoholPercentage::NODE_NAME => ['value' => '4.30'],
                        ReferenceUnitPrice::NODE_NAME => ['value' => '80.00'],
                        UnitPrice::NODE_NAME => ['value' => '1063.97'],
                        AdditionalTaxTable::NODE_NAME => [
                            AdditionalTax::NODE_NAME => [
                                [
                                    TaxType::NODE_NAME => ['value' => '014'],
                                ],
                                [
                                    TaxType::NODE_NAME => ['value' => '031'],
                                ],
                            ]

                        ],
                        ItemAmount::NODE_NAME => ['value' => '250.00'],
                    ],
                ],
            ],

        ];

        $additionalTaxRates = [
            AdditionalTaxType::ISC_RUM_SPECIFIC => '617.93',
        ];

        try {
            $ecf = new ECF10($data);
            if(!$ecf) {
                $this->fail('Failed to create ecf from data');
                return;
            }
            $ecf->setAdditionalTaxRates($additionalTaxRates);
            $ecf->calculateTotals();
            $ecf = $ecf->sign($pfxFile, file_get_contents($pfxPassword));
            if(!$ecf) {
                $this->fail('Failed to sign ecf');
                return;
            }
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
            return;
        }

        $this->assertInstanceOf(ECF10::class, $ecf);
        echo PHP_EOL . PHP_EOL;

        echo $ecf->toXML();
    }
}