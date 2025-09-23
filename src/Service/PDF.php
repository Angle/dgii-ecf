<?php
namespace Angle\ECF\Service;

use Angle\ECF\Catalog\ECFType;
use Angle\ECF\Catalog\TaxType;
use Angle\ECF\Catalog\UnitType;
use Angle\ECF\Node\ECF10\ECF10;
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
        //Prepare item matrix for the table.
        $itemsMatrix = [];
        foreach($ecf->getItemDetails()->getItems() as $item)
        {
            //Initialize the item with all header keys
            $i = [];
            foreach($this->headers as $key => $props)
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

            if($item->getAdditionalTaxTable()) {
                foreach($item->getAdditionalTaxTable()->getAdditionalTaxes() as $at) {
                    if($at->getSpecificConsumptionTaxAmount()) {
                        $i[self::ISC_SPECIFIC] = $at->getSpecificConsumptionTaxAmount()->getValue();
                    }
                    if($at->getAdValoremConsumptionTaxAmount()) {
                        $i[self::ISC_AD_VALOREM] = $at->getAdValoremConsumptionTaxAmount()->getValue();
                    }
                }
            }
            if ($item->getBillingIndicator()->getValue() == BillingIndicator::ITBIS1) {
                $i[self::ITBIS] = bcmul($item->getItemAmount()->getValue(), bcdiv(TaxType::getRate(TaxType::ITBIS_18),100, 4));
            }
            if ($item->getBillingIndicator()->getValue() == BillingIndicator::ITBIS2) {
                $i[self::ITBIS] = bcmul($item->getItemAmount()->getValue(), bcdiv(TaxType::getRate(TaxType::ITBIS_16),100, 4));
            }
            if ($item->getBillingIndicator()->getValue() == BillingIndicator::ITBIS3) {
                $i[self::ITBIS] = 0;
            }
            //discount
            //recharge
            $i[self::AMOUNT] = $item->getItemAmount()->getValue();
            $itemsMatrix[] = $i;
        }

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
                if(!array_key_exists($key, $headerMatrix)) {
                    unset($item[$key]);
                }
            }
            $filteredItemsMatrix[] = $item;
        }
        $itemsMatrix = $filteredItemsMatrix;

        unset($filteredItemsMatrix);

        //At this point we should have a items matrix where all items have all the headers used in same order and null where null

        //Now adjust description column to fit width
        //We add 7 px per column to account for 6px of padding + 1 of border.
        //We also add the size for each header
        $totalSize = 1;
        $totalSize += (count($existingHeaders) * 7);
        foreach ($headerMatrix as $key => $props) {
            $totalSize += $props["size"];
        }
        //Target size is 199
        $difference = 199 - $totalSize;

        //Adjust the size
        $headerMatrix[self::DESCRIPTION]["size"] += $difference;



        $totals = [];

        //totals:
        if($ecf->getHeader()->getTotals()->getTotalTaxableAmount()) {
            $totals['totalTaxableAmount'] = $ecf->getHeader()->getTotals()->getTotalTaxableAmount()->getValue();
        }
        if($ecf->getHeader()->getTotals()->getExemptAmount()) {
            $totals['totalExemptAmount'] = $ecf->getHeader()->getTotals()->getExemptAmount()->getValue();
        }
        if($ecf->getHeader()->getTotals()->getTotalItbis()) {
            $totals['totalExemptAmount'] = $ecf->getHeader()->getTotals()->getTotalItbis()->getValue();
        }
        //isc total
        //cdt total
        //tip total
        //dscount total
        //chargers total


        $html = $this->twig->render('pdf.html.twig', [
            'ecf'   => $ecf,
            'logo' => $logoFilePath,
            'items' => $itemsMatrix,
            'itemHeaders' => $headerMatrix,
            'totals' => $totals
        ]);

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
        return $pdfContent;
    }

    public function getLastError()
    {
        return $this->error;
    }
}