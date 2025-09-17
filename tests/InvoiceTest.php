<?php

namespace Angle\ECF\Tests;

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
use Angle\ECF\Node\ECF10\Header\Totals;
use Angle\ECF\Node\ECF10\Header\Totals\ItbisT1;
use Angle\ECF\Node\ECF10\Header\Totals\TaxableAmountT1;
use Angle\ECF\Node\ECF10\Header\Totals\TotalAmount;
use Angle\ECF\Node\ECF10\Header\Totals\TotalItbisT1;
use Angle\ECF\Node\ECF10\Header\Version;
use Angle\ECF\Node\ECF10\ItemDetails;
use Angle\ECF\Node\ECF10\ItemDetails\Item;
use Angle\ECF\Node\ECF10\ItemDetails\Item\BillingIndicator;
use Angle\ECF\Node\ECF10\ItemDetails\Item\GoodOrServiceIndicator;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ItemAmount;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ItemName;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ItemQuantity;
use Angle\ECF\Node\ECF10\ItemDetails\Item\LineNumber;
use Angle\ECF\Node\ECF10\ItemDetails\Item\UnitPrice;
use PHPUnit\Framework\TestCase;

final class InvoiceTest extends TestCase
{
    public function testInvoiceCreation(): void
    {
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
                // Subsection: Invoice Totals.
                Totals::NODE_NAME => [
                    // Subtotal for items taxed at ITBIS Rate 1 (18%).
                    TaxableAmountT1::NODE_NAME => ['value' => '450.00'],
                    // Tax rate for ITBIS Rate 1.
                    ItbisT1::NODE_NAME => ['value' => '18'],
                    // Total calculated ITBIS for Rate 1 (450.00 * 0.18).
                    TotalItbisT1::NODE_NAME => ['value' => '81.00'],
                    // Grand total (MontoGravadoI1 + TotalITBIS1). Mandatory.
                    TotalAmount::NODE_NAME => ['value' => '531.00'],
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
                ],
            ],

        ];

        try {
            $ecf = new ECF10($data);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
            return;
        }

        $this->assertInstanceOf(ECF10::class, $ecf);
        echo PHP_EOL . PHP_EOL;

        print_r($ecf);

        echo $ecf->toXML();
    }
}