<?php
namespace Angle\ECF\Service;

use Angle\ECF\Catalog\AdditionalTaxType;
use Angle\ECF\Catalog\ECFType;
use Angle\ECF\Catalog\TaxType;
use Angle\ECF\Catalog\UnitType;
use Angle\ECF\Node\ECF10\ECF10;
use Angle\ECF\Node\ECF10\ItemDetails\Item;
use Angle\ECF\Node\ECF10\ItemDetails\Item\BillingIndicator;
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


    //header map including names and default sizes
    private $headers = [
        self::QUANTITY => [
            "name" => "Cantidad",
            "size" => 11,
            "textAlign" => "center",
        ],
        self::DESCRIPTION => [
            "name" => "Descripción",
            "size" => 30,
            "textAlign" => "left",
        ],
        self::UNIT_OF_MEASURE => [
            "name" => "Unidad de Medida",
            "size" => 10,
            "textAlign" => "center",
        ],
        self::ALCOHOL_PERCENTAGE => [
            "name" => "Grados Alcohol en %",
            "size" => 8,
            "textAlign" => "right",
        ],
        self::REFERENCE_UNIT_PRICE => [
            "name" => "PVPci",
            "size" => 6,
            "textAlign" => "right",
        ],
        self::PRICE => [
            "name" => "Precio",
            "size" => 13,
            "textAlign" => "right",
        ],
        self::ISC_SPECIFIC => [
            "name" => "ISCe",
            "size" => 11,
            "textAlign" => "right",
        ],
        self::ISC_AD_VALOREM => [
            "name" => "ISCav",
            "size" => 11,
            "textAlign" => "right",
        ],
        self::ITBIS => [
            "name" => "ITBIS",
            "size" => 12,
            "textAlign" => "right",
        ],
        self::AMOUNT => [
            "name" => "Valor",
            "size" => 16,
            "textAlign" => "right",
        ],
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
            'totals' => $totals
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
                        'value' => $page->getPageTotalTaxableAmount()->getValue(),
                    ];
                }
                if ($page->getPageExemptAmount()) {
                    $totals [] = [
                        'name' => 'Subtotal Exento Página',
                        'value' => $page->getPageExemptAmount()->getValue(),
                    ];
                }
                if ($page->getPageTotalItbis()) {
                    $totals [] = [
                        'name' => 'Subtotal ITBIS Página',
                        'value' => $page->getPageTotalItbis()->getValue(),
                    ];
                }
                if ($page->getPageAdditionalTaxAmount()) {
                    $totals [] = [
                        'name' => 'Subtotal Impuesto Adicional Página',
                        'value' => $page->getPageAdditionalTaxAmount()->getValue(),
                    ];
                    if ($page->getSubtotalAdditionalTax()->getPageSpecificConsumptionTaxAmount()) {
                        $totals [] = [
                            'name' => 'Subtotal Impuesto Selectivo al Consumo Página',
                            'value' => $page->getSubtotalAdditionalTax()->getPageSpecificConsumptionTaxAmount()->getValue(),
                        ];
                    }
                    if ($page->getSubtotalAdditionalTax()->getPageOtherTaxesSubtotal()) {
                        $totals [] = [
                            'name' => 'Subtotal Otros Impuestos Adicionales Página',
                            'value' => $page->getSubtotalAdditionalTax()->getPageOtherTaxesSubtotal()->getValue(),
                        ];
                    }
                }

                $totals[] = [
                    'name' => 'Monto Total Página',
                    'value' => $page->getPageSubtotalAmount()->getValue(),
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
            'pagesMatrix' => $pagesMatrix
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
        //We can either calculate it in a specific passed items array or if none is passed we do it over all the items on the ecf
        if (count($items) == 0) {
            $items = $ecf->getItemDetails()->getitems();
        }

        //Prepare item matrix for the table.
        $itemsMatrix = [];

        foreach ($items as $item)
        {
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
                $i[self::REFERENCE_UNIT_PRICE] = $item->getReferenceUnitPrice()->getValue();
            }
            $i[self::PRICE] = $item->getUnitPrice()->getValue();

            if ($item->getIscSpecific()) {
                $i[self::ISC_SPECIFIC] = $item->getIscSpecific($ecf->getAdditionalTaxRates());
            }

            if ($item->getIscAdValorem()) {
                $i[self::ISC_AD_VALOREM] = $item->getIscAdValorem($ecf->getAdditionalTaxRates());
            }

            $itbis = $item->getItbis();
            if($itbis != null) {
                $i[self::ITBIS] = $itbis;
            }

            //discount
            //recharge
            $i[self::AMOUNT] = $item->getItemAmount()->getValue();
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
        $totals = [];

        if ($ecf->getHeader()->getTotals()->getTotalTaxableAmount()) {
            $totals[] = [
                'name' => 'Subtotal Gravado',
                'value' => $ecf->getHeader()->getTotals()->getTotalTaxableAmount()->getValue(),
            ];
        }
        if ($ecf->getHeader()->getTotals()->getExemptAmount()) {
            $totals[] = [
                'name' => 'Subtotal Exento',
                'value' => $ecf->getHeader()->getTotals()->getExemptAmount()->getValue(),
            ];
        }
        if ($ecf->getHeader()->getTotals()->getTotalItbis()) {
            $totals[] = [
                'name' => 'Total ITBIS',
                'value' => $ecf->getHeader()->getTotals()->getTotalItbis()->getValue(),
            ];
        }

        //Calculate other totals from totals - additional tax table
        if ($ecf->getHeader()->getTotals()->getAdditionalTaxesTable()) {
            $iscTotal = 0;
            $cdtTotal = 0;
            $tipTotal = 0;
            $otherTotal = 0;

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
            if ($iscTotal != 0) {
                $totals[] = [
                    'name' => 'Total ISC',
                    'value' => $iscTotal,
                ];
            }
            if ($cdtTotal != 0) {
                $totals[] = [
                    'name' => 'CDT',
                    'value' => $cdtTotal,
                ];
            }
            if ($tipTotal != 0) {
                $totals[] = [
                    'name' => 'Propina Legal',
                    'value' => $tipTotal,
                ];
            }
            if ($otherTotal != 0) {
                $totals[] = [
                    'name' => 'Otros Impuestos',
                    'value' => $otherTotal,
                ];
            }

        }

        //TODO: discountTotal
        //TODO: echargesTotal

        $totals[] = [
            'name' => 'Total',
            'value' => $ecf->getHeader()->getTotals()->getTotalAmount()->getValue(),
        ];

        return $totals;
    }
}