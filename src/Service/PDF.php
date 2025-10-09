<?php
namespace Angle\ECF\Service;

use Angle\ECF\Catalog\AdditionalTaxType;
use Angle\ECF\Catalog\ECFType;
use Angle\ECF\Catalog\TaxType;
use Angle\ECF\Catalog\UnitType;
use Angle\ECF\Node\ECF10\ECF10;
use Angle\ECF\Node\ECF10\ItemDetails\Item;
use Angle\ECF\Node\ECF10\ItemDetails\Item\BillingIndicator;
use Angle\ECF\Utility\Math;
use Exception;
use Twig\Environment as Twig;
use Spipu\Html2Pdf\Html2Pdf;


class PDF
{
    /** @var Twig $twig */
    private $twig;

    /** @var string $kernelProjectDir */
    private $kernelProjectDir;

    /** @var array */
    private $error;

    const QUANTITY = "quantity";
    const DESCRIPTION = "description";
    const UNIT_OF_MEASURE = "unitOfMeasure";
    const ALCOHOL_PERCENTAGE = "alcoholPercentage";
    const REFERENCE_UNIT_PRICE = "referenceUnitPrice";
    const PRICE = "price";
    const ISC_SPECIFIC = "iscSpecific";
    const ISC_AD_VALOREM = "iscAdValorem";
    const ITBIS = "itbis";
    const AMOUNT = "amount";
    const RECHARGE = "recharges";
    const DISCOUNT = "descuento";


    //header map including names and default sizes
    private $headers = [
        self::QUANTITY => [
            "name" => "Cantidad",
            "size" => 8,
            "textAlign" => "center",
        ],
        self::DESCRIPTION => [
            "name" => "Descripción",
            "size" => 10,
            "textAlign" => "left",
        ],
        self::UNIT_OF_MEASURE => [
            "name" => "Unidad de Medida",
            "size" => 7,
            "textAlign" => "center",
        ],
        self::ALCOHOL_PERCENTAGE => [
            "name" => "Grados Alcohol en %",
            "size" => 6,
            "textAlign" => "right",
        ],
        self::REFERENCE_UNIT_PRICE => [
            "name" => "PVPci",
            "size" => 4,
            "textAlign" => "right",
        ],
        self::PRICE => [
            "name" => "Precio",
            "size" => 10,
            "textAlign" => "right",
        ],
        self::ISC_SPECIFIC => [
            "name" => "ISCe",
            "size" => 9,
            "textAlign" => "right",
        ],
        self::ISC_AD_VALOREM => [
            "name" => "ISCav",
            "size" => 9,
            "textAlign" => "right",
        ],
        self::ITBIS => [
            "name" => "ITBIS",
            "size" => 12,
            "textAlign" => "right",
        ],
        self::DISCOUNT => [
            "name" => "Descuento",
            "size" => 9,
            "textAlign" => "right",
        ],
        self::RECHARGE => [
            "name" => "Recargo",
            "size" => 9,
            "textAlign" => "right",
        ],
        self::AMOUNT => [
            "name" => "Valor",
            "size" => 13,
            "textAlign" => "right",
        ]
    ];


    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param ECF10 $ecf
     * @return false|string
     */
    public function build(ECF10 $ecf, ?string $logoFilePath = null): string
    {
        //If we detect a multi-page xml we use that method
        if ($ecf?->getHeader()?->getDocId()?->getTotalPages() && $ecf->getHeader()->getDocId()->getTotalPages()->getValue() > 1) {
            return $this->buildMultiPage($ecf, $logoFilePath);
        }

        //First we get the raw items matrix from the ecf item details
        $itemsMatrix = $this->calculateItemsMatrix($ecf);

        //Then from that items matrix we get the header matrix and the items matrix ONLY for the used headers
        [$headerMatrix, $itemsMatrix] = $this->getHeadersAndFilteredItemsMatrix($itemsMatrix);

        //Then we adjust the size fields of the headerMatrix according to the headerMatrix
        $headerMatrix = $this->adjustHeaderSizes($headerMatrix);

        //Then we calculate totals.
        $totals = $this->calculateTotals($ecf);

        //Render the html
        $html = $this->twig->render('pdf.html.twig', [
            'ecf'   => $ecf,
            'logo' => $logoFilePath,
            'items' => $itemsMatrix,
            'itemHeaders' => $headerMatrix,
            'totals' => $totals,
            'otherCurrency' => $ecf?->getHeader()?->getOtherCurrency()?->getCurrencyType()->getValue(),
        ]);

        //Turn the html into pdf content
        try {
            $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, "UTF-8", [8, 5, 8, 5]);
            $html2pdf->pdf->SetDisplayMode('real');
            $html2pdf->setTestIsImage(true);
            $html2pdf->writeHTML($html);
            $filename = ''; // filename is ignored when exporting as string
            $pdfContent = $html2pdf->output($filename, 'S'); // Dest: 'S' means String
        } catch (Exception $e) {
            // PDF building error..
            $this->error = [
                'type' => 'pdf',
                'code' => -1,
                'msg' => $e->getMessage(),
            ];
            return false;
        }

        //Return the pdf content
        return $pdfContent;
    }

    /**
     * @param ECF10 $ecf
     * @param mixed $logoFilePath
     * @return string
     */
    public function buildMultiPage(ECF10 $ecf, ?string $logoFilePath = null): string
    {
        $hasOtherCurrency = $ecf?->getHeader()?->getOtherCurrency() != null;
        $exchangeRate = $ecf?->getHeader()?->getOtherCurrency()?->getExchangeRate()?->getValue();

        //Create an array of items with the NumeroLinea as key so we can easilya access them.
        $items = [];
        foreach ($ecf->getItemDetails()->getItems() as $item) {
            $items[$item->getLineNumber()->getValue()] = $item;
        }

        //Get item table header matrix
        $allItemsMatrix = $this->calculateItemsMatrix($ecf);
        $existingHeaders = [];
        foreach ($allItemsMatrix as $i => $item) {
            foreach ($item as $key => $props) {
                if ($props == null) {
                    continue;
                }
                if (!in_array($key, $existingHeaders)) {
                    $existingHeaders[] = $key;
                }
            }
        }
        $headerMatrix = [];
        foreach ($this->headers as $key => $props) {
            if (in_array($key, $existingHeaders)) {
                $headerMatrix[$key] = $props;
            }
        }

        //Lets adjust column size according to column count/size
        $headerMatrix = $this->adjustHeaderSizes($headerMatrix);

        $pagesMatrix = [];
        foreach ($ecf->getPagination()->getPages() as $page) {
            $pageData = [
                'pageNumber' => $page->getPageNumber()->getValue(),
            ];

            $pageItems = [];
            foreach($items as $key => $props) {
                if ($props->getLineNumber()->getValue() >= $page->getLineFrom()->getValue() && $props->getLineNumber()->getValue() <= $page->getLineTo()->getValue()) {
                    $pageItems[$key] = $props;
                }
            }

            $itemsMatrix = $this->calculateItemsMatrix($ecf, $pageItems);

            //Remove unused headers in itemsMatrix
            $filteredItemsMatrix = [];
            foreach ($itemsMatrix as $i => $item) {
                foreach ($item as $key => $props) {
                    if (!array_key_exists($key, $headerMatrix)) {
                        unset($item[$key]);
                    }
                }
                $filteredItemsMatrix[] = $item;
            }
            $itemsMatrix = $filteredItemsMatrix;

            $pageData['items'] = $itemsMatrix;

            $totals = [];
            //If not the last page, get page totals
            if ($page->getPageNumber()->getValue() != $ecf->getHeader()->getDocId()->getTotalPages()->getValue()) {
                if ($page->getPageTotalTaxableAmount()) {
                    $totals [] = [
                        'name' => 'Subtotal Gravado Página',
                        'value' => number_format($hasOtherCurrency ? Math::round(Math::div($page->getPageTotalTaxableAmount()->getValue(), $exchangeRate),2)  : $page->getPageTotalTaxableAmount()->getValue(),2),
                    ];
                }
                if ($page->getPageExemptAmount()) {
                    $totals [] = [
                        'name' => 'Subtotal Exento Página',
                        'value' => number_format($hasOtherCurrency ? Math::round(Math::div($page->getPageExemptAmount()->getValue(), $exchangeRate),2) : $page->getPageExemptAmount()->getValue(),2),
                    ];
                }
                if ($page->getPageTotalItbis()) {
                    $totals [] = [
                        'name' => 'Subtotal ITBIS Página',
                        'value' => number_format($hasOtherCurrency ? Math::round(Math::div($page->getPageTotalItbis()->getValue(), $exchangeRate),2) : $page->getPageTotalItbis()->getValue(),2),
                    ];
                }
                if ($page->getPageAdditionalTaxAmount()) {
                    $totals [] = [
                        'name' => 'Subtotal Impuesto Adicional Página',
                        'value' => number_format($hasOtherCurrency ? Math::round(Math::div($page->getPageAdditionalTaxAmount()->getValue(), $exchangeRate),2) : $page->getPageAdditionalTaxAmount()->getValue(),2),
                    ];
                    if ($page->getSubtotalAdditionalTax()->getPageSpecificConsumptionTaxAmount()) {
                        $totals [] = [
                            'name' => 'Subtotal Impuesto Selectivo al Consumo Página',
                            'value' => number_format($hasOtherCurrency ? Math::round(Math::div($page->getSubtotalAdditionalTax()->getPageSpecificConsumptionTaxAmount()->getValue(), $exchangeRate),2) : $page->getSubtotalAdditionalTax()->getPageSpecificConsumptionTaxAmount()->getValue(),2),
                        ];
                    }
                    if ($page->getSubtotalAdditionalTax()->getPageOtherTaxesSubtotal()) {
                        $totals [] = [
                            'name' => 'Subtotal Otros Impuestos Adicionales Página',
                            'value' => number_format($hasOtherCurrency ? Math::round(Math::div($page->getSubtotalAdditionalTax()->getPageOtherTaxesSubtotal()->getValue(), $exchangeRate),2) : $page->getSubtotalAdditionalTax()->getPageOtherTaxesSubtotal()->getValue(),2),
                        ];
                    }
                }

                $totals[] = [
                    'name' => 'Monto Total Página',
                    'value' => number_format($hasOtherCurrency ? Math::round(Math::div($page->getPageSubtotalAmount()->getValue(), $exchangeRate),2) : $page->getPageSubtotalAmount()->getValue(),2),
                ];
            } else { //If last page, get totals of everything
                //Then we calculate totals.
                $totals = $this->calculateTotals($ecf);
            }

            $pageData['totals'] = $totals;

            $pagesMatrix[$page->getPageNumber()->getValue()] = $pageData;
        }

        //Render the html
        $html = $this->twig->render('multi-pdf.html.twig', [
            'ecf'   => $ecf,
            'logo' => $logoFilePath,
            'itemHeaders' => $headerMatrix,
            'pagesMatrix' => $pagesMatrix,
            'otherCurrency' => $ecf?->getHeader()?->getOtherCurrency()?->getCurrencyType()->getValue(),
        ]);



        //Turn the html into pdf content
        try {
            $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, "UTF-8", [8, 5, 8, 5]);
            $html2pdf->pdf->SetDisplayMode('real');
            $html2pdf->setTestIsImage(true);
            $html2pdf->writeHTML($html);
            $filename = ''; // filename is ignored when exporting as string
            $pdfContent = $html2pdf->output($filename, 'S'); // Dest: 'S' means String
        } catch (Exception $e) {
            // PDF building error..
            $this->error = [
                'type' => 'pdf',
                'code' => -1,
                'msg' => $e->getMessage(),
            ];
            return false;
        }

        //Return the pdf content
        return $pdfContent;

    }

    public function getLastError()
    {
        return $this->error;
    }

    /**
     * Summary of calculateItemsMatrix
     * @param ECF10 $ecf
     * @param Item[] $items
     * @return array<array>
     */
    private function calculateItemsMatrix(ECF10 $ecf, $items = [])
    {
        $hasOtherCurrency = $ecf?->getHeader()?->getOtherCurrency() != null;

        //We can either calculate it in a specific passed items array or if none is passed we do it over all the items on the ecf
        if (count($items) == 0) {
            $items = $ecf->getItemDetails()->getitems();
        }

        //Prepare item matrix for the table.
        $itemsMatrix = [];

        foreach ($items as $item)
        {
            if($hasOtherCurrency) {
                $total = $item->getOtherCurrencyDetails()->getOtherCurrencyItemAmount()->getValue();
            } else {
                $total = $item->getItemAmount()->getValue();
            }

            //Initialize the item with all header keys
            $i = [];
            foreach ($this->headers as $key => $props)
            {
                $i[$key] = null;
            }

            $description = $item->getItemName()->getValue();
            //If item is exempt, prefix description with an E
            if ($item->getBillingIndicator()->getValue() == BillingIndicator::EXEMPT)
            {
                $description = 'E ' . $description;
            }

            $i[self::QUANTITY] = $item->getItemQuantity()->getValue();
            $i[self::DESCRIPTION] = $description;
            if ($item->getUnitOfMeasure())
            {
                $i[self::UNIT_OF_MEASURE] = UnitType::getName($item->getUnitOfMeasure()->getValue());
            }
            if ($item->getAlcoholPercentage())
            {
                $i[self::ALCOHOL_PERCENTAGE] = $item->getAlcoholPercentage()->getValue();
            }
            if ($item->getReferenceUnitPrice())
            {
                $i[self::REFERENCE_UNIT_PRICE] = number_format($item->getReferenceUnitPrice()->getValue(),2);
            }

            if($hasOtherCurrency) {
                $i[self::PRICE] = number_format($item->getOtherCurrencyDetails()->getOtherCurrencyPrice()->getValue(),2);
            } else {
                $i[self::PRICE] = number_format($item->getUnitPrice()->getValue(),2);
            }

            if($iscSpecific = $item->getIscSpecific($ecf->getAdditionalTaxRates())) {
                if($hasOtherCurrency) {
                    $iscSpecific = Math::div($iscSpecific, $ecf->getHeader()->getOtherCurrency()->getExchangeRate()->getValue());
                }

                $i[self::ISC_SPECIFIC] = number_format(Math::round($iscSpecific,2),2);
                $total = Math::add($total, $iscSpecific);
            }

            if ($iscAdValorem = $item->getIscAdValorem($ecf->getAdditionalTaxRates())) {
                if($hasOtherCurrency) {
                    $iscAdValorem = Math::div($iscAdValorem, $ecf->getHeader()->getOtherCurrency()->getExchangeRate()->getValue());
                }
                $i[self::ISC_AD_VALOREM] = number_format(Math::round($iscAdValorem,2),2);
                $total = Math::add($total, $iscAdValorem);
            }
            if($itbis = $item->getItbis($ecf->getAdditionalTaxRates())) {
                if($hasOtherCurrency) {
                    $itbis = $item->getItbisOtherCurrency($ecf->getHeader()->getOtherCurrency()->getExchangeRate()->getValue(), $ecf->getAdditionalTaxRates());
                }

                $i[self::ITBIS] = number_format($itbis,2);
                $total = Math::add($total, $itbis);
            }

            if($hasOtherCurrency) {
                if($item?->getOtherCurrencyDetails()?->getOtherCurrencyDiscount()) {
                    $discount = number_format(Math::round($item->getOtherCurrencyDetails()->getOtherCurrencyDiscount()->getValue(),2),2);
                    $i[self::DISCOUNT] = '-' . $discount;
                }
            } else {
                if($item?->getDiscountAmount()) {
                    $discount = number_format(Math::round($item->getDiscountAmount()->getValue(),2),2);
                    $i[self::DISCOUNT] = '-' . $discount;
                }
            }

            if($hasOtherCurrency) {
                if($item?->getOtherCurrencyDetails()?->getOtherCurrencySurcharge()) {
                    $recharge = number_format(Math::round($item->getOtherCurrencyDetails()->getOtherCurrencySurcharge()->getValue(),2),2);
                    $i[self::RECHARGE] = $recharge;

                }
            } else {
                if($item?->getSurchargeAmount()) {
                    $recharge = number_format(Math::round($item->getSurchargeAmount()->getValue(),2),2);
                    $i[self::RECHARGE] = $recharge;
                }
            }

            $i[self::AMOUNT] = number_format(Math::round($total,2),2);
            $itemsMatrix[] = $i;
        }
        return $itemsMatrix;
    }

    private function getHeadersAndFilteredItemsMatrix(array $itemsMatrix):array
    {
        //We remove the non existing headers.
        //As long as an item does have the header present, we count the header.
        //This can leave fields null which we will fill with "-" on rendering side.
        //But since we need every column possible for every header we leave all headers with atleast 1 item present
        //1. get all existing headers
        $existingHeaders = [];
        foreach ($itemsMatrix as $i => $item) {
            foreach ($item as $key => $props) {
                if ($props == null) {
                    continue;
                }
                if (!in_array($key, $existingHeaders)) {
                    $existingHeaders[] = $key;
                }
            }
        }

        //2. create a filtered header matrix with just the used headers
        $headerMatrix = [];
        foreach ($this->headers as $key => $props) {
            if (in_array($key, $existingHeaders)) {
                $headerMatrix[$key] = $props;
            }
        }

        //3. Remove the unused headers from items
        $filteredItemsMatrix = [];
        foreach ($itemsMatrix as $i => $item) {
            foreach ($item as $key => $props) {
                if (!array_key_exists($key, $headerMatrix)) {
                    unset($item[$key]);
                }
            }
            $filteredItemsMatrix[] = $item;
        }
        $itemsMatrix = $filteredItemsMatrix;
        return [$headerMatrix, $filteredItemsMatrix];
    }

    private function adjustHeaderSizes(array $headerMatrix): array
    {
        //At this point we should have a items matrix where all items have all the headers used in same order and null where null

        //Now adjust description column to fit width
        //We add 7 px per column to account for 6px of padding + 1 of border.
        //We also add the size for each header
        $totalSize = 1;
        $totalSize += (count($headerMatrix) * 7);
        foreach ($headerMatrix as $key => $props) {
            $totalSize += $props["size"];
        }

        //Target size is 199
        $difference = 199 - $totalSize;

        //Adjust the size
        $headerMatrix[self::DESCRIPTION]["size"] += $difference;

        return $headerMatrix;
    }

    private function calculateTotals(ECF10 $ecf)
    {
        $hasOtherCurrency = $ecf?->getHeader()?->getOtherCurrency() != null;

        $totals = [];

        if ($ecf->getHeader()->getTotals()->getTotalTaxableAmount()) {
            $totals[] = [
                'name' => 'Subtotal Gravado',
                'value' => number_format($hasOtherCurrency ? $ecf->getHeader()->getOtherCurrency()->getOtherCurrencyTotalTaxableAmount()->getValue() : $ecf->getHeader()->getTotals()->getTotalTaxableAmount()->getValue(),2),
            ];
        }
        if ($ecf->getHeader()->getTotals()->getExemptAmount()) {
            $totals[] = [
                'name' => 'Subtotal Exento',
                'value' => number_format($hasOtherCurrency ? $ecf->getHeader()->getOtherCurrency()->getOtherCurrencyExemptAmount()->getValue() : $ecf->getHeader()->getTotals()->getExemptAmount()->getValue(),2),
            ];
        }
        if ($ecf->getHeader()->getTotals()->getTotalItbis()) {
            $totals[] = [
                'name' => 'Total ITBIS',
                'value' => number_format($hasOtherCurrency ? $ecf->getHeader()->getOtherCurrency()->getOtherCurrencyTotalItbis()->getValue() : $ecf->getHeader()->getTotals()->getTotalItbis()->getValue(),2),
            ];
        }

        //Calculate other totals from totals - additional tax table
        if ($ecf->getHeader()->getTotals()->getAdditionalTaxesTable()) {
            $iscTotal = 0;
            $cdtTotal = 0;
            $tipTotal = 0;
            $otherTotal = 0;

            if($hasOtherCurrency) {
                foreach ($ecf->getHeader()->getOtherCurrency()->getOtherCurrencyAdditionalTaxesTable()->getOtherCurrencyAdditionalTaxes() as $otherAdditionalTax) {
                    if ($otherAdditionalTax->getOtherCurrencySpecificConsumptionTaxAmount()) {
                        $iscTotal += $otherAdditionalTax->getOtherCurrencySpecificConsumptionTaxAmount()->getValue();
                    }
                    if ($otherAdditionalTax->getOtherCurrencyAdValoremConsumptionTaxAmount()) {
                        $iscTotal += $otherAdditionalTax->getOtherCurrencyAdValoremConsumptionTaxAmount()->getValue();
                    }
                    if ($otherAdditionalTax->getOtherCurrencyOtherAdditionalTaxes()) {
                        switch ($otherAdditionalTax->getOtherCurrencyTaxType()->getValue()) {
                            case AdditionalTaxType::LEGAL_TIP:
                                $tipTotal += $otherAdditionalTax->getOtherCurrencyOtherAdditionalTaxes()->getValue();
                                break;
                            case AdditionalTaxType::CDT:
                                $cdtTotal += $otherAdditionalTax->getOtherCurrencyOtherAdditionalTaxes()->getValue();
                                break;
                            default:
                                $otherTotal *= $otherAdditionalTax->getOtherCurrencyOtherAdditionalTaxes()->getValue();
                                break;
                        }
                    }
                }
            } else {
                foreach ($ecf->getHeader()->getTotals()->getAdditionalTaxesTable()->getAdditionalTaxes() as $additionalTax) {
                    if ($additionalTax->getSpecificConsumptionTaxAmount()) {
                        $iscTotal += $additionalTax->getSpecificConsumptionTaxAmount()->getValue();
                    }
                    if ($additionalTax->getAdValoremConsumptionTaxAmount()) {
                        $iscTotal += $additionalTax->getAdValoremConsumptionTaxAmount()->getValue();
                    }
                    if ($additionalTax->getOtherAdditionalTaxes()) {
                        switch ($additionalTax->getTaxType()->getValue()) {
                            case AdditionalTaxType::LEGAL_TIP:
                                $tipTotal += $additionalTax->getOtherAdditionalTaxes()->getValue();
                                break;
                            case AdditionalTaxType::CDT:
                                $cdtTotal += $additionalTax->getOtherAdditionalTaxes()->getValue();
                                break;
                            default:
                                $otherTotal *= $additionalTax->getOtherAdditionalTaxes()->getValue();
                                break;
                        }
                    }
                }
            }


            if ($iscTotal != 0) {
                $totals[] = [
                    'name' => 'Total ISC',
                    'value' => number_format($iscTotal,2),
                ];
            }
            if ($cdtTotal != 0) {
                $totals[] = [
                    'name' => 'CDT',
                    'value' => number_format($cdtTotal,2),
                ];
            }
            if ($tipTotal != 0) {
                $totals[] = [
                    'name' => 'Propina Legal',
                    'value' => number_format($tipTotal,2),
                ];
            }
            if ($otherTotal != 0) {
                $totals[] = [
                    'name' => 'Otros Impuestos',
                    'value' => number_format($otherTotal,2),
                ];
            }

        }

        //TODO: discountTotal
        //TODO: echargesTotal

        $totals[] = [
            'name' => 'Total',
            'value' => number_format($hasOtherCurrency ? $ecf->getHeader()->getOtherCurrency()->getOtherCurrencyTotalAmount()->getValue() : $ecf->getHeader()->getTotals()->getTotalAmount()->getValue(),2),
        ];

        return $totals;
    }
}