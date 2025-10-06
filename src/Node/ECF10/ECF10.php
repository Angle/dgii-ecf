<?php

namespace Angle\ECF\Node\ECF10;

use Angle\ECF\Catalog\AdditionalTaxType;
use Angle\ECF\Catalog\ECFType;
use Angle\ECF\Catalog\TaxType as CatalogTaxType;
use Angle\ECF\Catalog\UnitType;
use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\ECFInterface;
use Angle\ECF\Node\ECF10\Header\DocId\TotalPages;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyAdditionalTaxesTable;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyAdditionalTaxesTable\OtherCurrencyAdditionalTax;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyAdditionalTaxesTable\OtherCurrencyAdditionalTax\OtherCurrencyAdditionalTaxRate;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyAdditionalTaxesTable\OtherCurrencyAdditionalTax\OtherCurrencyAdValoremConsumptionTaxAmount;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyAdditionalTaxesTable\OtherCurrencyAdditionalTax\OtherCurrencySpecificConsumptionTaxAmount;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyAdditionalTaxesTable\OtherCurrencyAdditionalTax\OtherCurrencyTaxType;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyExemptAmount;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyTaxableAmountT1;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyTaxableAmountT2;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyTaxableAmountT3;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyTotalItbis;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyTotalItbisT1;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyTotalTaxableAmount;
use Angle\ECF\Node\ECF10\Header\Totals;
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxAmount;
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable;
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable\AdditionalTax;
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable\AdditionalTax\AdditionalTaxRate;
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable\AdditionalTax\AdValoremConsumptionTaxAmount;
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable\AdditionalTax\SpecificConsumptionTaxAmount;
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable\AdditionalTax\TaxType as AdditionalTaxTaxType;
use Angle\ECF\Node\ECF10\Header\Totals\ExemptAmount;
use Angle\ECF\Node\ECF10\Header\Totals\ItbisT1;
use Angle\ECF\Node\ECF10\Header\Totals\ItbisT2;
use Angle\ECF\Node\ECF10\Header\Totals\ItbisT3;
use Angle\ECF\Node\ECF10\Header\Totals\NonBillableAmount;
use Angle\ECF\Node\ECF10\Header\Totals\TaxableAmountT1;
use Angle\ECF\Node\ECF10\Header\Totals\TaxableAmountT2;
use Angle\ECF\Node\ECF10\Header\Totals\TaxableAmountT3;
use Angle\ECF\Node\ECF10\Header\Totals\TotalAmount;
use Angle\ECF\Node\ECF10\Header\Totals\TotalItbis;
use Angle\ECF\Node\ECF10\Header\Totals\TotalItbisT1;
use Angle\ECF\Node\ECF10\Header\Totals\TotalItbisT2;
use Angle\ECF\Node\ECF10\Header\Totals\TotalItbisT3;
use Angle\ECF\Node\ECF10\Header\Totals\TotalTaxableAmount;
use Angle\ECF\Node\ECF10\ItemDetails\Item\AdditionalTaxTable\AdditionalTax\TaxType;
use Angle\ECF\Node\ECF10\ItemDetails\Item\BillingIndicator;
use Angle\ECF\Node\ECF10\Pagination\Page;
use Angle\ECF\Node\ECF10\Pagination\Page\LineFrom;
use Angle\ECF\Node\ECF10\Pagination\Page\LineTo;
use Angle\ECF\Node\ECF10\Pagination\Page\PageAdditionalTaxAmount;
use Angle\ECF\Node\ECF10\Pagination\Page\PageExemptAmount;
use Angle\ECF\Node\ECF10\Pagination\Page\PageItbisT1;
use Angle\ECF\Node\ECF10\Pagination\Page\PageItbisT2;
use Angle\ECF\Node\ECF10\Pagination\Page\PageItbisT3;
use Angle\ECF\Node\ECF10\Pagination\Page\PageNonBillableAmount;
use Angle\ECF\Node\ECF10\Pagination\Page\PageNumber;
use Angle\ECF\Node\ECF10\Pagination\Page\PageSubtotalAmount;
use Angle\ECF\Node\ECF10\Pagination\Page\PageTaxableAmountT1;
use Angle\ECF\Node\ECF10\Pagination\Page\PageTaxableAmountT2;
use Angle\ECF\Node\ECF10\Pagination\Page\PageTaxableAmountT3;
use Angle\ECF\Node\ECF10\Pagination\Page\PageTotalItbis;
use Angle\ECF\Node\ECF10\Pagination\Page\PageTotalTaxableAmount;
use Angle\ECF\Node\ECF10\Pagination\Page\SubtotalAdditionalTax;
use Angle\ECF\Node\ECF10\Pagination\Page\SubtotalAdditionalTax\PageOtherTaxesSubtotal;
use Angle\ECF\Node\ECF10\Pagination\Page\SubtotalAdditionalTax\PageSpecificConsumptionTaxAmount;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyTotalItbisT2;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyAdditionalTaxAmount;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyTotalAmount;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyTotalItbisT3;
use Angle\ECF\Service\SignatureGenerator;
use Angle\ECF\Utility\Math;
use DateTime;
use DateTimeZone;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;
use DOMXPath;
use Exception;

use function PHPSTORM_META\type;

/**
 * @method static ECF10 createFromDOMNode(DOMNode $node)
 */
class ECF10 extends ECFNode implements ECFInterface
{
    #########################
    ##       CATALOG       ##
    #########################


    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = 'ECF';

    const LOW_AMOUNT = '250000';

    const QR_CODE_BASE_DOMAIN_LOW_AMOUNT = 'https://fc.dgii.gov.do/eCF/ConsultaTimbreFC?';

    const QR_CODE_BASE_DOMAIN_HIGH_AMOUNT = 'https://ecf.dgii.gov.do/ecf/ConsultaTimbre?';

    protected static $baseAttributes = [];


    ################################
    ## PROPERTY NAME TRANSLATIONS ##
    ################################

    protected static $attributes = [];

    protected static $children = [
        'header' => [
            'keywords'  => ['Encabezado', 'header'],
            'class'     => Header::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'itemDetails' => [
            'keywords'  => ['DetallesItems', 'itemDetails'],
            'class'     => ItemDetails::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'subtotals' => [
            'keywords'  => ['Subtotales', 'subtotals'],
            'class'     => Subtotals::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'discountsOrSurcharges' => [
            'keywords'  => ['DescuentosORecargos', 'descuentosORecargos'],
            'class'     => DiscountsOrSurcharges::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'pagination' => [
            'keywords'  => ['Paginacion', 'pagination'],
            'class'     => Pagination::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'referenceInformation' => [
            'keywords'  => ['InformacionReferencia', 'referenceInformation'],
            'class'     => ReferenceInformation::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'signatureTimestamp' => [
            'keywords'  => ['FechaHoraFirma', 'signatureTimestamp'],
            'class'     => SignatureTimestamp::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /**
     * @var Header
     */
    protected $header;

    /**
     * @var ItemDetails
     */
    protected $itemDetails;

    /**
     * @var Subtotals|null
     */
    protected $subtotals;

    /**
     * @var DiscountsOrSurcharges|null
     */
    protected $discountsOrSurcharges;

    /**
     * @var Pagination|null
     */
    protected $pagination;

    /**
     * @var ReferenceInformation|null
     */
    protected $referenceInformation;

    /**
     * @var SignatureTimestamp
     */
    protected $signatureTimestamp;

    /**
     * @var DOMNode
     */
    protected $signature;

    /**
     * @var string|null
     */
    protected $originalXml = null;

    /**
     * @var array
     * This allows the user to inject their own additional tax rates if library is not up to date or they want to use older catalogues
     * Format example: [AdditionalTaxType::BEER_SPECIFIC => '217.39', AdditionalTaxType::BEER_AD_VALOREM => '.01']
     */
    protected $additionalTaxRates = [];

    #########################
    ##     CONSTRUCTOR     ##
    #########################

    /**
     * @param DOMNode[]
     * @throws ECFException
     */
    public function setChildrenFromDOMNodes(array $children): void
    {
        foreach ($children as $node) {
            if ($node instanceof DOMText) {
                // TODO: we are skipping the actual text inside the Node.. is this useful?
                // TODO: DOMText
                continue;
            }

            switch ($node->localName) {
                case Header::NODE_NAME:
                    $header = Header::createFromDOMNode($node);
                    $this->setHeader($header);
                    break;
                case ItemDetails::NODE_NAME:
                    $issuer = ItemDetails::createFromDOMNode($node);
                    $this->setItemDetails($issuer);
                    break;
                case Subtotals::NODE_NAME:
                    $subtotals = Subtotals::createFromDOMNode($node);
                    $this->setSubtotals($subtotals);
                    break;
                case DiscountsOrSurcharges::NODE_NAME:
                    $discountsOrSurcharges = DiscountsOrSurcharges::createFromDOMNode($node);
                    $this->setDiscountsOrSurcharges($discountsOrSurcharges);
                    break;
                case Pagination::NODE_NAME:
                    $pagination = Pagination::createFromDOMNode($node);
                    $this->setPagination($pagination);
                    break;
                case ReferenceInformation::NODE_NAME:
                    $referenceInformation = ReferenceInformation::createFromDOMNode($node);
                    $this->setReferenceInformation($referenceInformation);
                    break;
                case SignatureTimestamp::NODE_NAME:
                    $signatureTimestamp = SignatureTimestamp::createFromDOMNode($node);
                    $this->setSignatureTimestamp($signatureTimestamp);
                    break;
                case "Signature":
                    $this->signature = $node;
                default:
                    //throw new ECFException(sprintf("Unknown children node '%s' in %s", $node->nodeName, self::NODE_NS_NAME));
            }
        }
    }


    #########################
    ## ECF TO DOM TRANSLATION
    #########################

    public function toDOMElement(DOMDocument $dom): DOMElement
    {
        $node = $dom->createElement(self::NODE_NAME);

        foreach ($this->getAttributes() as $attr => $value) {
            $node->setAttribute($attr, $value);
        }

        // Header Node
        if ($this->header) {
            // TODO: What happens if the header is not set?
            $headerNode = $this->header->toDOMElement($dom);
            $node->appendChild($headerNode);
        }

        // ItemDetails Node
        if ($this->itemDetails) {
            // TODO: What happens if the itemDetails is not set?
            $itemDetailsNode = $this->itemDetails->toDOMElement($dom);
            $node->appendChild($itemDetailsNode);
        }

        // Subtotals Node
        if ($this->subtotals) {
            // TODO: What happens if the subtotals is not set?
            $subtotalsNode = $this->subtotals->toDOMElement($dom);
            $node->appendChild($subtotalsNode);
        }

        // DiscountsOrSurcharges Node
        if ($this->discountsOrSurcharges) {
            // TODO: What happens if the discountsOrSurcharges is not set?
            $discountsOrSurchargesNode = $this->discountsOrSurcharges->toDOMElement($dom);
            $node->appendChild($discountsOrSurchargesNode);
        }

        // Pagination Node
        if ($this->pagination) {
            // TODO: What happens if the pagination is not set?
            $paginationNode = $this->pagination->toDOMElement($dom);
            $node->appendChild($paginationNode);
        }

        // ReferenceInformation Node
        if ($this->referenceInformation) {
            // TODO: What happens if the referenceInformation is not set?
            $referenceInformationNode = $this->referenceInformation->toDOMElement($dom);
            $node->appendChild($referenceInformationNode);
        }


        // SignatureTimestamp Node
        if ($this->signatureTimestamp) {
            // TODO: What happens if the signatureTimestamp is not set?
            $signatureTimestampNode = $this->signatureTimestamp->toDOMElement($dom);
            $node->appendChild($signatureTimestampNode);
        }

        if ($this->signature instanceof DOMNode) {
            if ($this->signature->ownerDocument !== $dom) {
                // Import the node to make it "belong" to the current document
                $importedSignatureNode = $dom->importNode($this->signature, true); // `true` to import with children
                $node->appendChild($importedSignatureNode);
            } else {
                // The node is already from the same document, so just append it
                $node->appendChild($this->signature);
            }
        }

        return $node;
    }


    #########################
    ##      ECF TO XML    ##
    #########################

    public function toDOMDocument(): DOMDocument
    {
        $dom = new \DOMDocument('1.0','UTF-8');
        $dom->preserveWhiteSpace = false;

        $ecfNode = $this->toDOMElement($dom);
        $dom->appendChild($ecfNode);

        return $dom;
    }

    // TODO: DOMDocument duplicates the Namespace declarations of any child
    public function toXML()
    {
        return $this->toDOMDocument()->saveXML();
    }


    #########################
    ## VALIDATION
    #########################

    public function validate(): bool
    {
        // TODO: implement the full set of validation, including type and Business Logic

        return true;
    }


    #########################
    ##   SPECIAL METHODS   ##
    #########################

    //Calculate taxes and totals from the items
    public function calculateTotals()
    {
        //TODO: Throw exceptions
        if (!$this->getItemDetails()) {
            return;
        }
        if (!$this->getItemDetails()->getItems()) {
            return;
        }
        if (count($this->getItemDetails()->getItems()) == 0) {
            return;
        }

        //TODO: Account for recharges and discounts

        $itbis1TaxableAmount = 0;
        $itbis2TaxableAmount = 0;
        $itbis3TaxableAmount = 0;
        $exemptAmount = 0;
        $nonBilledAmount = 0;
        $additionalTaxesAmount = 0;

        $iscAmount = 0; //We keep track of this for ease of Itbis1 calculation, whose base is itbis1taxable+iscamount

        //First get all tax data
        $additionalTaxes = [];
        foreach ($this->getItemDetails()->getItems() as $item) {
            switch ($item->getBillingIndicator()->getValue()) {
                case BillingIndicator::NOT_BILLED:
                    $nonBilledAmount = Math::add($nonBilledAmount, $item->getItemAmount()->getValue());
                    break;
                case BillingIndicator::ITBIS1:
                    $itbis1TaxableAmount = Math::add($itbis1TaxableAmount, $item->getItemAmount()->getValue());
                    break;
                case BillingIndicator::ITBIS2:
                    $itbis2TaxableAmount = Math::add($itbis2TaxableAmount, $item->getItemAmount()->getValue());
                    break;
                case BillingIndicator::ITBIS3:
                    $itbis3TaxableAmount = Math::add($itbis3TaxableAmount, $item->getItemAmount()->getValue());
                    break;
                case BillingIndicator::EXEMPT:
                    $exemptAmount = Math::add($exemptAmount, $item->getItemAmount()->getValue());
                    break;
            }

            //Create a total/additionalTax for each itemdetails/additionalTax
            if ($item->getAdditionalTaxTable() && $item->getAdditionalTaxTable()->getAdditionalTaxes()) {
                foreach ($item->getAdditionalTaxTable()->getAdditionalTaxes() as $at) {
                    //TODO: Send this logic to additionalTax maybe

                    $additionalTax = new AdditionalTax([]);
                    $taxType = $at->getTaxType()->getValue();
                    $additionalTax->setTaxType(AdditionalTaxTaxType::newWithValue($taxType));

                    //Set the additional tax rate node
                    //Allow to manually inject rate for older invoices if we dont want to use the preset values
                    if (array_key_exists($taxType,$this->additionalTaxRates)) {
                        $additionalTax->setAdditionalTaxRate(AdditionalTaxRate::newWithValue($this->additionalTaxRates[$taxType]));
                    } else {
                        $additionalTax->setAdditionalTaxRate(AdditionalTaxRate::newWithValue(AdditionalTaxType::getRateDisplay($taxType)));
                    }

                    //Process entries with tax type 06-22
                    if (AdditionalTaxType::isISCSpecific($taxType)) {

                        $amount = $item->getIscSpecific($this->additionalTaxRates);
                        if ($amount == null) {
                            continue;
                        }

                        $iscAmount = Math::add($iscAmount, $amount);

                        $additionalTax->setSpecificConsumptionTaxAmount(SpecificConsumptionTaxAmount::newWithValue($amount));
                        $additionalTaxesAmount = Math::add($additionalTaxesAmount, $amount);

                    } elseif (AdditionalTaxType::isISCAdValorem($taxType)) { //Now we process 23-39
                        $amount = $item->getIscAdValorem($this->additionalTaxRates);
                        if ($amount == null) {
                            continue;
                        }

                        $iscAmount = Math::add($iscAmount, $amount);

                        $additionalTax->setAdValoremConsumptionTaxAmount(AdValoremConsumptionTaxAmount::newWithValue($amount));
                        $additionalTaxesAmount = Math::add($additionalTaxesAmount, $amount);
                    } else {
                        //TODO: Other taxes
                    }

                    $additionalTaxes[] = $additionalTax;
                }
            }
        }

        //Now lets start putting the data into the total node
        $totals = new Totals([]);

        $totalTaxableAmount = 0; //Gravable
        $totalItbisAmount = 0; //Monto
        $hasItbis = false;
        if ($itbis1TaxableAmount != 0) {
            $hasItbis = true;

            //First lets round to 2 decimals
            $itbis1TaxableAmount = Math::round($itbis1TaxableAmount,2);

            //Add rate node
            $totals->setItbisT1(ItbisT1::newWithValue(Math::round(Math::mul(CatalogTaxType::getRate(CatalogTaxType::ITBIS_1),100),0)));

            //Add taxable amount node
            $totals->setTaxableAmountT1(TaxableAmountT1::newWithValue($itbis1TaxableAmount));

            //Add to taxable amount total taxable amount sum
            $totalTaxableAmount = Math::add($totalTaxableAmount, $itbis1TaxableAmount);

            //Add tax amount node
            $itbis1Total = Math::mul(Math::add($itbis1TaxableAmount, $iscAmount), CatalogTaxType::getRate(CatalogTaxType::ITBIS_1));
            $itbis1Total = Math::round($itbis1Total, 2);

            $totals->setTotalItbisT1(TotalItbisT1::newWithValue($itbis1Total));

            //Add tax amount to total tax amount sum
            $totalItbisAmount = Math::add($totalItbisAmount, $itbis1Total);
        }
        if ($itbis2TaxableAmount != 0) {
            $hasItbis = true;

            //First lets round to 2 decimals
            $itbis2TaxableAmount = Math::round($itbis2TaxableAmount,2);

            //Add rate node
            $totals->setItbisT2(ItbisT2::newWithValue(Math::round(Math::mul(CatalogTaxType::getRate(CatalogTaxType::ITBIS_2),100),0)));

            //Add taxable amount node
            $totals->setTaxableAmountT2(TaxableAmountT2::newWithValue($itbis2TaxableAmount));

            //Add to taxable amount total taxable amount sum
            $totalTaxableAmount = Math::add($totalTaxableAmount, $itbis2TaxableAmount);

            //Add tax amount node
            $itbis2Total = Math::mul($itbis2TaxableAmount, CatalogTaxType::getRate(CatalogTaxType::ITBIS_2));
            $itbis2Total = Math::round($itbis2Total, 2);

            $totals->setTotalItbisT2(TotalItbisT2::newWithValue($itbis2Total));

            //Add tax amount to total tax amount sum
            $totalItbisAmount = Math::add($totalItbisAmount, $itbis2Total);
        }
        if ($itbis3TaxableAmount != 0) {
            $hasItbis = true;

            //First lets round to 2 decimals
            $itbis3TaxableAmount = Math::round($itbis3TaxableAmount,2);

            //Add rate node
            $totals->setItbisT3(ItbisT3::newWithValue(Math::round(Math::mul(CatalogTaxType::getRate(CatalogTaxType::ITBIS_3),100),0)));

            //Add taxable amount node
            $totals->setTaxableAmountT3(TaxableAmountT3::newWithValue($itbis3TaxableAmount));

            //Add to taxable amount total taxable amount sum
            $totalTaxableAmount = Math::add($totalTaxableAmount, $itbis3TaxableAmount);

            //Add tax amount node
            $itbis3Total = Math::mul($itbis3TaxableAmount, CatalogTaxType::getRate(CatalogTaxType::ITBIS_3));
            $itbis3Total = Math::round($itbis3Total, 2);

            $totals->setTotalItbisT3(TotalItbisT3::newWithValue($itbis3Total));

            //Add tax amount to total tax amount sum
            $totalItbisAmount = Math::add($totalItbisAmount, $itbis3Total);
        }

        //Now we create the total nodes related to total itbis
        if ($hasItbis) {
            //First we round
            $totalTaxableAmount = Math::round($totalTaxableAmount,2);
            $totalItbisAmount = Math::round($totalItbisAmount,2);

            //And now we create the nodes
            $totals->setTotalTaxableAmount(TotalTaxableAmount::newWithValue($totalTaxableAmount));
            $totals->setTotalItbis(TotalItbis::newWithValue($totalItbisAmount));
        }

        if ($exemptAmount != 0) {
            $totals->setExemptAmount(ExemptAmount::newWithValue($exemptAmount));
        }

        if ($nonBilledAmount != 0) {
            $totals->setNonBillableAmount(NonBillableAmount::newWithValue($nonBilledAmount));
        }

        if (count($additionalTaxes) != 0) {
            $additionalTaxesTable = new AdditionalTaxesTable([]);
            $additionalTaxesTable->setAdditionalTaxes($additionalTaxes);

            $totals->setAdditionalTaxesTable($additionalTaxesTable);

            $totals->setAdditionalTaxAmount(AdditionalTaxAmount::newWithValue(Math::round($additionalTaxesAmount,2)));
        }

        //Finally we set the total amount (MontoGravadoTotal + Monto Exento + Total ITBIS + Monto del impuesto adicional)
        $totalAmount = Math::add($totalTaxableAmount, $exemptAmount);
        $totalAmount = Math::add($totalAmount, $totalItbisAmount);
        $totalAmount = Math::add($totalAmount, $additionalTaxesAmount);
        $totalAmount = Math::round($totalAmount,2);
        $totals->setTotalAmount(TotalAmount::newWithValue($totalAmount));

        $this->getHeader()->setTotals($totals);

        //Todo: retention total amounts (ITBISRetenido,ISRRetenido, ITBISPercepcion, ISRPercepcion)
    }

    public function CalculateOtherCurrencyTotals()
    {

        $itbis1TaxableAmount = 0;
        $itbis2TaxableAmount = 0;
        $itbis3TaxableAmount = 0;
        $exemptAmount = 0;
        $nonBilledAmount = 0;
        $additionalTaxesAmount = 0;

        $iscAmount = 0; //We keep track of this for ease of Itbis1 calculation, whose base is itbis1taxable+iscamount

        //First get all tax data
        $additionalTaxes = [];
        foreach ($this->getItemDetails()->getItems() as $item) {
            if($item?->getOtherCurrencyDetails()) {
                //TODO: return exception
            }
            switch ($item->getBillingIndicator()->getValue()) {
                case BillingIndicator::NOT_BILLED:
                    $nonBilledAmount = Math::add($nonBilledAmount, $item->getOtherCurrencyDetails()->getOtherCurrencyItemAmount()->getValue());
                    break;
                case BillingIndicator::ITBIS1:
                    $itbis1TaxableAmount = Math::add($itbis1TaxableAmount, $item->getOtherCurrencyDetails()->getOtherCurrencyItemAmount()->getValue());
                    break;
                case BillingIndicator::ITBIS2:
                    $itbis2TaxableAmount = Math::add($itbis2TaxableAmount, $item->getOtherCurrencyDetails()->getOtherCurrencyItemAmount()->getValue());
                    break;
                case BillingIndicator::ITBIS3:
                    $itbis3TaxableAmount = Math::add($itbis3TaxableAmount, $item->getOtherCurrencyDetails()->getOtherCurrencyItemAmount()->getValue());
                    break;
                case BillingIndicator::EXEMPT:
                    $exemptAmount = Math::add($exemptAmount, $item->getOtherCurrencyDetails()->getOtherCurrencyItemAmount()->getValue());
                    break;
            }

            //Create a total/additionalTax for each itemdetails/additionalTax
            if ($item->getAdditionalTaxTable() && $item->getAdditionalTaxTable()->getAdditionalTaxes()) {
                foreach ($item->getAdditionalTaxTable()->getAdditionalTaxes() as $at) {
                    //TODO: Send this logic to additionalTax maybe

                    $additionalTax = new OtherCurrencyAdditionalTax([]);
                    $taxType = $at->getTaxType()->getValue();
                    $additionalTax->setOtherCurrencyTaxType(OtherCurrencyTaxType::newWithValue($taxType));

                    //Set the additional tax rate node
                    //Allow to manually inject rate for older invoices if we dont want to use the preset values
                    if (array_key_exists($taxType,$this->additionalTaxRates)) {
                        $additionalTax->setOtherCurrencyAdditionalTaxRate(OtherCurrencyAdditionalTaxRate::newWithValue($this->additionalTaxRates[$taxType]));
                    } else {
                        $additionalTax->setOtherCurrencyAdditionalTaxRate(OtherCurrencyAdditionalTaxRate::newWithValue(AdditionalTaxType::getRateDisplay($taxType)));
                    }



                    //Process entries with tax type 06-22
                    if (AdditionalTaxType::isISCSpecific($taxType)) {

                        //First we get the amount in DOP
                        $amountAsDOP = $item->getIscSpecific($this->additionalTaxRates);
                        if ($amountAsDOP == null) {
                            continue;
                        }

                        //Then we convert to other currency by diving over the exchange rate.
                        $amount = Math::round(Math::div($amountAsDOP, $this->getHeader()->getOtherCurrency()->getExchangeRate()->getValue()), 2);

                        $iscAmount = Math::add($iscAmount, $amount);

                        $additionalTax->setOtherCurrencySpecificConsumptionTaxAmount(OtherCurrencySpecificConsumptionTaxAmount::newWithValue($amount));
                        $additionalTaxesAmount = Math::add($additionalTaxesAmount, $amount);

                    } elseif (AdditionalTaxType::isISCAdValorem($taxType)) { //Now we process 23-39
                        $amountAsDOP = $item->getIscAdValorem($this->additionalTaxRates);
                        if ($amountAsDOP == null) {
                            continue;
                        }

                        $amount = Math::round(Math::div($amountAsDOP, $this->getHeader()->getOtherCurrency()->getExchangeRate()->getValue()),2);

                        $iscAmount = Math::add($iscAmount, $amount);

                        $additionalTax->setOtherCurrencyAdValoremConsumptionTaxAmount(OtherCurrencyAdValoremConsumptionTaxAmount::newWithValue($amount));
                        $additionalTaxesAmount = Math::add($additionalTaxesAmount, $amount);
                    } else {
                        //TODO: Other taxes
                    }

                    $additionalTaxes[] = $additionalTax;
                }
            }
        }

        //Now lets start putting the data into the total node
        // $totals = new Totals([]);

        $totalTaxableAmount = 0; //Gravable
        $totalItbisAmount = 0; //Monto
        $hasItbis = false;
        if ($itbis1TaxableAmount != 0) {
            $hasItbis = true;

            //First lets round to 2 decimals
            $itbis1TaxableAmount = Math::round($itbis1TaxableAmount,2);

            //Add taxable amount node
            $this->getHeader()->getOtherCurrency()->setOtherCurrencyTaxableAmountT1(OtherCurrencyTaxableAmountT1::newWithValue($itbis1TaxableAmount));

            //Add to taxable amount total taxable amount sum
            $totalTaxableAmount = Math::add($totalTaxableAmount, $itbis1TaxableAmount);

            //Add tax amount node
            $itbis1Total = Math::mul(Math::add($itbis1TaxableAmount, $iscAmount), CatalogTaxType::getRate(CatalogTaxType::ITBIS_1));
            $itbis1Total = Math::round($itbis1Total, 2);

            $this->getHeader()->getOtherCurrency()->setOtherCurrencyTotalItbisT1(OtherCurrencyTotalItbisT1::newWithValue($itbis1Total));

            //Add tax amount to total tax amount sum
            $totalItbisAmount = Math::add($totalItbisAmount, $itbis1Total);
        }
        if ($itbis2TaxableAmount != 0) {
            $hasItbis = true;

            //First lets round to 2 decimals
            $itbis2TaxableAmount = Math::round($itbis2TaxableAmount,2);

            //Add taxable amount node
            $this->getHeader()->getOtherCurrency()->setOtherCurrencyTaxableAmountT2(OtherCurrencyTaxableAmountT2::newWithValue($itbis2TaxableAmount));

            //Add to taxable amount total taxable amount sum
            $totalTaxableAmount = Math::add($totalTaxableAmount, $itbis2TaxableAmount);

            //Add tax amount node
            $itbis2Total = Math::mul($itbis2TaxableAmount, CatalogTaxType::getRate(CatalogTaxType::ITBIS_2));
            $itbis2Total = Math::round($itbis2Total, 2);

            $this->getHeader()->getOtherCurrency()->setOtherCurrencyTotalItbisT2(OtherCurrencyTotalItbisT2::newWithValue($itbis2Total));

            //Add tax amount to total tax amount sum
            $totalItbisAmount = Math::add($totalItbisAmount, $itbis2Total);
        }
        if ($itbis3TaxableAmount != 0) {
            $hasItbis = true;

            //First lets round to 2 decimals
            $itbis3TaxableAmount = Math::round($itbis3TaxableAmount,2);

            //Add taxable amount node
            $this->getHeader()->getOtherCurrency()->setOtherCurrencyTaxableAmountT3(OtherCurrencyTaxableAmountT3::newWithValue($itbis3TaxableAmount));

            //Add to taxable amount total taxable amount sum
            $totalTaxableAmount = Math::add($totalTaxableAmount, $itbis3TaxableAmount);

            //Add tax amount node
            $itbis3Total = Math::mul($itbis3TaxableAmount, CatalogTaxType::getRate(CatalogTaxType::ITBIS_3));
            $itbis3Total = Math::round($itbis3Total, 2);

            $this->getHeader()->getOtherCurrency()->setOtherCurrencyTotalItbisT3(OtherCurrencyTotalItbisT3::newWithValue($itbis3Total));

            //Add tax amount to total tax amount sum
            $totalItbisAmount = Math::add($totalItbisAmount, $itbis3Total);
        }

        //Now we create the total nodes related to total itbis
        if ($hasItbis) {
            //First we round
            $totalTaxableAmount = Math::round($totalTaxableAmount,2);
            $totalItbisAmount = Math::round($totalItbisAmount,2);

            //And now we create the nodes
            $this->getHeader()->getOtherCurrency()->setOtherCurrencyTotalTaxableAmount(OtherCurrencyTotalTaxableAmount::newWithValue($totalTaxableAmount));
            $this->getHeader()->getOtherCurrency()->setOtherCurrencyTotalItbis(OtherCurrencyTotalItbis::newWithValue($totalItbisAmount));
        }

        if ($exemptAmount != 0) {
            $this->getHeader()->getOtherCurrency()->setOtherCurrencyExemptAmount(OtherCurrencyExemptAmount::newWithValue($exemptAmount));
        }

        if (count($additionalTaxes) != 0) {
            $additionalTaxesTable = new OtherCurrencyAdditionalTaxesTable([]);
            $additionalTaxesTable->setOtherCurrencyAdditionalTaxes($additionalTaxes);

            $this->getHeader()->getOtherCurrency()->setOtherCurrencyAdditionalTaxesTable($additionalTaxesTable);

            $this->getHeader()->getOtherCurrency()->setOtherCurrencyAdditionalTaxAmount(OtherCurrencyAdditionalTaxAmount::newWithValue(Math::round($additionalTaxesAmount,2)));
        }

        //Finally we set the total amount (MontoGravadoTotal + Monto Exento + Total ITBIS + Monto del impuesto adicional)
        $totalAmount = Math::add($totalTaxableAmount, $exemptAmount);
        $totalAmount = Math::add($totalAmount, $totalItbisAmount);
        $totalAmount = Math::add($totalAmount, $additionalTaxesAmount);
        $totalAmount = Math::round($totalAmount,2);
        $this->getHeader()->getOtherCurrency()->setOtherCurrencyTotalAmount(OtherCurrencyTotalAmount::newWithValue($totalAmount));
    }

    //Goes through its totals->additionalTaxTable and creates populates the additionalTaxRates property from the values in there.
    //This makes sure we have the correct additionalTaxRates on the object for pdf purposes
    //If we dont do this, the pdf would just use the preset every time which we dont want, we want it to be tied to whats in the xml instead
    public function generateAdditionalTaxRates() {
        $additionalTaxRates = [];

        if ($this?->getHeader()?->getTotals()?->getAdditionalTaxesTable()?->getAdditionalTaxes()) {
            foreach ($this->getHeader()->getTotals()->getAdditionalTaxesTable()->getAdditionalTaxes() as $at) {
                if (array_key_exists($at->getTaxType()->getValue(), $additionalTaxRates) && $additionalTaxRates[$at->getTaxType()->getValue()] != $at->getAdditionalTaxRate()->getValue()) {
                    //TODO: Exception, we shouldn't have two different values for the same additionalTaxType
                    continue;
                }
                $additionalTaxRates[$at->getTaxType()->getValue()] = $at->getAdditionalTaxRate()->getValue();
                //TODO: Maybe transform the format depending on if its rate or fee
            }
        }

        $this->additionalTaxRates = $additionalTaxRates;
    }

    /**
     * @return string|null
     */
    public function getOriginalXml(): ?string
    {
        return $this->originalXml;
    }

    /**
     * @param string|null $originalXml
     * @return self
     */
    public function setOriginalXml(?string $originalXml)
    {
        $this->originalXml = $originalXml;
        return $this;
    }

    public function getEcfType(): ?string
    {
        return $this->getHeader()?->getDocId()?->getEcfType()?->getValue();
    }

    public function getEcfTypeName(): ?string
    {
        $ecfTypeValue = $this->getHeader()?->getDocId()?->getEcfType()?->getValue();
        if (!$ecfTypeValue) {
            return null;
        }

        return ECFType::getName($ecfTypeValue);
    }

    public function getEncf(): ?string
    {
        return $this->getHeader()?->getDocId()?->getEncf()?->getValue();
    }

    public function getSequenceExpirationDate(): ?string
    {
        return $this->getHeader()?-> getDocId()?->getSequenceExpirationDate()?->getValue();
    }

    public function isTaxCreditInvoice(): bool
    {
        return $this->getEcfType() == ECFType::TAX_CREDIT_INVOICE;
    }

    public function isCreditNote(): bool
    {
        return $this->getEcfType() == ECFType::CREDIT_NOTE;
    }

    public function isDebitNote(): bool
    {
        return $this->getEcfType() == ECFType::DEBIT_NOTE;
    }

    public function getIssuerLegalName(): ?string
    {
        return $this->getHeader()?->getIssuer()?->getLegalName()?->getValue();
    }

    public function getIssuerCompanyName(): ?string
    {
        return $this->getHeader()?->getIssuer()?->getCompanyName()?->getValue();

    }

    public function getIssuerBranchName(): ?string
    {
        return $this->getHeader()?->getIssuer()?->getBranch()?->getValue();
    }

    public function getIssuerRnc(): ?string
    {
        return $this->getHeader()?->getIssuer()?->getIssuerRnc()?->getValue();
    }

    public function getIssuerAddress(): ?string
    {
        return $this->getHeader()?->getIssuer()?->getIssuerAddress()?->getValue();
    }

    public function getIssueDate(): ?string
    {
        return $this->getHeader()?->getIssuer()?->getIssueDate()?->getValue();
    }

    public function getRecipientCompanyName(): ?string
    {
        return $this->getHeader()?->getRecipient()?->getCompanyName()?->getValue();
    }

    public function getRecipientRnc(): ?string
    {
        return $this->getHeader()?->getRecipient()?->getRecipientRnc()?->getValue();
    }

    public function sign($pfxFile, $pfxPassword): ECF10|bool
    {
        $signatureTimestamp = SignatureTimestamp::newWithValue( (new DateTime("now", new DateTimeZone("America/Caracas")))->format("d-m-Y H:i:s"));
        $this->setSignatureTimestamp($signatureTimestamp);

        $xml = $this->toXML();
        $signatureGenerator = new SignatureGenerator();

        try {
            $signedXml = $signatureGenerator->signXml($pfxFile, $pfxPassword, $xml);
        } catch(Exception $e) {
            $this->setSignatureTimestamp(null);
            return false;
        }

        if (!$signedXml) {
            $this->setSignatureTimestamp(null);
            return false;
        }

        //Now that we have the signed xml lets an ECF10 object with it and return that
        $dom = new DOMDocument();
        $dom->loadXML($signedXml);
        $ecfNode = $dom->firstChild;
        $signedEcf = ECF10::createFromDOMNode($ecfNode);
        $signedEcf->generateAdditionalTaxRates();

        return $signedEcf;
    }


    public function getQr(): ?string
    {
        if (!$this->signature) return null;
        if (!$this->getSecurityCode()) return null;

        if ($this->getHeader()->getTotals()->getTotalAmount()->getValue() >= self::LOW_AMOUNT) {
            $qrCode = self::QR_CODE_BASE_DOMAIN_HIGH_AMOUNT;
            $qrCode .= 'RncEmisor=' . $this->getIssuerRnc() . '&';
            $qrCode .= 'RncComprador=' . $this->getRecipientRnc() . '&';
            $qrCode .= 'ENCF=' . $this->getEncf() . '&';
            $qrCode .= 'FechaEmision=' . $this->getIssueDate() . '&';
            $qrCode .= 'MontoTotal=' . number_format($this->getHeader()->getTotals()->getTotalAmount()->getValue(), 2,'.','') . '&';
            $qrCode .= 'FechaFirma=' . str_replace(" ", "%20", $this->getSignatureTimestamp()->getValue()) . '&';
            $qrCode .= 'CodigoSeguridad=' . $this->getSecurityCode();
        }
        else {
            $qrCode = self::QR_CODE_BASE_DOMAIN_LOW_AMOUNT;
            $qrCode .= 'RncEmisor=' . $this->getIssuerRnc() . '&';
            $qrCode .= 'ENCF=' . $this->getEncf() . '&';
            $qrCode .= 'MontoTotal=' . number_format($this->getHeader()->getTotals()->getTotalAmount()->getValue(), 2,'.','') . '&';
            $qrCode .= 'CodigoSeguridad=' . $this->getSecurityCode();
        }

        return $qrCode;
    }

    public function getSignatureTimestampValue(): ?string
    {
        return $this?->getSignatureTimestamp()?->getValue();
    }

    public function getSecurityCode(): ?string
    {
        $dom = $this->toDOMDocument();

        $xPath = new DOMXPath($dom);

        $xPath->registerNamespace('ds', 'http://www.w3.org/2000/09/xmldsig#');
        $nodes = $xPath->query('//ds:SignatureValue');
        if ($nodes->length > 0) {
            // Access the first (and only) node in the list
            $singleNode = $nodes->item(0);

            // Get the string value from that node
            return substr($singleNode->nodeValue,0,6);
        }

        return false;
    }

    /**
     * Generates and adds the pagination node with its children along with the totalPages node
     * @return void
     */
    public function createPagination(int $itemsPerPage): ECF10
    {
        if ($this?->getItemDetails()?->getItems()) {
            $totalItemsCount = count($this->getItemDetails()->getItems());
            $pageCount = ceil($totalItemsCount / $itemsPerPage);

            //No pagination required
            if($pageCount < 2) {
                return $this;
            }
            $this->getHeader()->getDocId()->setTotalPages(TotalPages::newWithValue($pageCount));
            $pagination = new Pagination([]);

            //Get itemdetails matrix ordered by key being lineNumber so we can access it by that index
            $itemsMatrix = [];
            foreach($this->getItemDetails()->getItems() as $item) {
                $itemsMatrix[$item->getLineNumber()->getValue()] = $item;
            }

            for ($pageNum = 0; $pageNum < $pageCount; $pageNum++) {
                $page = new Page([]);
                $page->setPageNumber(PageNumber::newWithValue($pageNum+1));
                $lineFrom = ($itemsPerPage * $pageNum) + 1;
                $lineTo = $lineFrom + $itemsPerPage - 1;
                if ($lineTo > $totalItemsCount) {
                    $lineTo = $totalItemsCount;
                }

                $page->setLineFrom(LineFrom::newWithValue($lineFrom));
                $page->setLineTo(LineTo::newWithValue($lineTo));

                $itbis1TaxableAmount = 0;
                $itbis2TaxableAmount = 0;
                $itbis3TaxableAmount = 0;

                $notBilledAmount = 0;
                $exemptAmount = 0;

                $iscAmount = 0;
                $otherAdditionalTaxAmount = 0;
                for ($i = $lineFrom; $i <= $lineTo; $i++) {
                    $itemDetails = $itemsMatrix[$i];

                    switch($itemDetails->getBillingIndicator()->getValue()) {
                        case BillingIndicator::NOT_BILLED:
                            $notBilledAmount = Math::add($notBilledAmount, $itemDetails->getItemAmount()->getValue());
                            break;
                        case BillingIndicator::ITBIS1:
                            $itbis1TaxableAmount = Math::add($itbis1TaxableAmount, $itemDetails->getItemAmount()->getValue());
                            break;
                        case BillingIndicator::ITBIS2:
                            $itbis2TaxableAmount = Math::add($itbis2TaxableAmount, $itemDetails->getItemAmount()->getValue());
                            break;
                        case BillingIndicator::ITBIS3:
                            $itbis3TaxableAmount = Math::add($itbis3TaxableAmount, $itemDetails->getItemAmount()->getValue());
                            break;
                        case BillingIndicator::EXEMPT:
                            $exemptAmount = Math::add($exemptAmount, $itemDetails->getItemAmount()->getValue());
                            break;
                    }

                    $iscSpecific = $itemDetails->getIscSpecific();
                    if($iscSpecific != null) $iscAmount = Math::add($iscAmount, $iscSpecific);
                    $iscAdValorem = $itemDetails->getIscAdValorem();
                    if($iscAdValorem != null) $iscAmount = Math::add($iscAmount, $iscAdValorem);

                    //TODO: Other
                }

                //Set itbis1,2,3 taxable
                //Set itbis total taxable
                //Set itbis1,2,3 amount
                //Set itbis total amount
                $itbisTotalTaxableAmount = 0;
                $itbisTotalAmount = 0;
                if($itbis1TaxableAmount > 0) {
                    //First we round to 2 decimals
                    $itbis1TaxableAmount = Math::round($itbis1TaxableAmount,2);

                    //Now we set it to its node
                    $page->setPageTaxableAmountT1(PageTaxableAmountT1::newWithValue($itbis1TaxableAmount));

                    //Now we add it to the total taxable amount
                    $itbisTotalTaxableAmount = Math::add($itbisTotalTaxableAmount, $itbis1TaxableAmount);

                    //Now we calculate the rounded amount
                    $itbis1Amount = Math::round(Math::mul(CatalogTaxType::getRate(CatalogTaxType::ITBIS_1), $itbis1TaxableAmount),2);

                    //Now we set the rounded taxed amount node
                    $page->setPageItbisT1(PageItbisT1::newWithValue($itbis1Amount));

                    //And finally we add the taxed amount to the total taxed amount
                    $itbisTotalAmount = Math::add($itbisTotalAmount, $itbis1Amount);
                }
                if($itbis2TaxableAmount > 0) {
                    //First we round to 2 decimals
                    $itbis2TaxableAmount = Math::round($itbis2TaxableAmount,2);

                    //Now we set it to its node
                    $page->setPageTaxableAmountT2(PageTaxableAmountT2::newWithValue($itbis2TaxableAmount));

                    //Now we add it to the total taxable amount
                    $itbisTotalTaxableAmount = Math::add($itbisTotalTaxableAmount, $itbis2TaxableAmount);

                    //Now we calculate the rounded amount
                    $itbis2Amount = Math::round(Math::mul(CatalogTaxType::getRate(CatalogTaxType::ITBIS_2), $itbis2TaxableAmount),2);

                    //Now we set the rounded taxed amount node
                    $page->setPageItbisT2(PageItbisT2::newWithValue($itbis2Amount));

                    //And finally we add the taxed amount to the total taxed amount
                    $itbisTotalAmount = Math::add($itbisTotalAmount, $itbis2Amount);
                }
                if($itbis3TaxableAmount > 0) {
                    //First we round to 2 decimals
                    $itbis3TaxableAmount = Math::round($itbis3TaxableAmount,2);

                    //Now we set it to its node
                    $page->setPageTaxableAmountT3(PageTaxableAmountT3::newWithValue($itbis3TaxableAmount));

                    //Now we add it to the total taxable amount
                    $itbisTotalTaxableAmount = Math::add($itbisTotalTaxableAmount, $itbis3TaxableAmount);

                    //Now we calculate the rounded amount
                    $itbis3Amount = Math::round(Math::mul(CatalogTaxType::getRate(CatalogTaxType::ITBIS_3), $itbis2TaxableAmount),2);

                    //Now we set the rounded taxed amount node
                    $page->setPageItbisT3(PageItbisT3::newWithValue($itbis3Amount));

                    //And finally we add the taxed amount to the total taxed amount
                    $itbisTotalAmount = Math::add($itbisTotalAmount, $itbis3Amount);
                }

                if($itbisTotalTaxableAmount > 0) {
                    $itbisTotalTaxableAmount = Math::round($itbisTotalTaxableAmount, 2);
                    $itbisTotalAmount = Math::round($itbisTotalAmount,2);
                    $page->setPageTotalTaxableAmount(PageTotalTaxableAmount::newWithValue($itbisTotalTaxableAmount));
                    $page->setPageTotalItbis(PageTotalItbis::newWithValue($itbisTotalAmount));
                }


                //Set exemptAmount if it exists
                if($exemptAmount > 0) {
                    $exemptAmount = Math::round($exemptAmount,2);
                    $page->setPageExemptAmount(PageExemptAmount::newWithValue($exemptAmount));
                }

                //Set Nonbillable amount if it exists
                if($notBilledAmount > 0) {
                    $page->setPageNonBillableAmount(PageNonBillableAmount::newWithValue($notBilledAmount));
                }

                //TODO: Other additional amount
                $totalAdditionalTaxAmount = 0;
                if($iscAmount > 0 || $otherAdditionalTaxAmount > 0) {
                    $additionalTaxTable = new SubtotalAdditionalTax([]);
                    if($iscAmount > 0) {
                        $iscAmount = Math::round($iscAmount, 2);
                        $additionalTaxTable->setPageSpecificConsumptionTaxAmount(PageSpecificConsumptionTaxAmount::newWithValue($iscAmount));
                    }
                    if($otherAdditionalTaxAmount > 0) {
                        $otherAdditionalTaxAmount = Math::round($otherAdditionalTaxAmount,2);
                        $additionalTaxTable->setPageOtherTaxesSubtotal(PageOtherTaxesSubtotal::newWithValue($otherAdditionalTaxAmount));
                    }
                    $page->setSubtotalAdditionalTax($additionalTaxTable);
                    $totalAdditionalTaxAmount = Math::round(Math::add($iscAmount, $otherAdditionalTaxAmount), 2);
                    $page->setPageAdditionalTaxAmount(PageAdditionalTaxAmount::newWithValue($totalAdditionalTaxAmount));
                }
                $pageSubtotalAmount = Math::add($itbisTotalTaxableAmount, $itbisTotalAmount);
                $pageSubtotalAmount = Math::add($pageSubtotalAmount, $exemptAmount);
                $pageSubtotalAmount = Math::add($pageSubtotalAmount, $totalAdditionalTaxAmount);

                $pageSubtotalAmount = Math::round($pageSubtotalAmount,2);
                $page->setPageSubtotalAmount(PageSubtotalAmount::newWithValue($pageSubtotalAmount));

                $pagination->addPage($page);
            }

            $this->setPagination($pagination);
        }

        return $this;
    }

    #########################
    ##  INTERFACE METHODS  ##
    #########################




    #########################
    ## GETTERS AND SETTERS ##
    #########################



    #########################
    ##       CHILDREN      ##
    #########################

    /**
     * @return Header
     */
    public function getHeader(): ?Header
    {
        return $this->header;
    }

    /**
     * @param Header $header
     * @return ECF10
     */
    public function setHeader(Header $header): self
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @return ItemDetails
     */
    public function getItemDetails(): ?ItemDetails
    {
        return $this->itemDetails;
    }

    /**
     * @param ItemDetails $itemDetails
     * @return ECF10
     */
    public function setItemDetails(ItemDetails $itemDetails): self
    {
        $this->itemDetails = $itemDetails;
        return $this;
    }

    /**
     * @return Subtotals
     */
    public function getSubtotals(): ?Subtotals
    {
        return $this->subtotals;
    }

    /**
     * @param Subtotals $subtotals
     * @return ECF10
     */
    public function setSubtotals(Subtotals $subtotals): self
    {
        $this->subtotals = $subtotals;
        return $this;
    }

    /**
     * @return DiscountsOrSurcharges
     */
    public function getDiscountsOrSurcharges(): ?DiscountsOrSurcharges
    {
        return $this->discountsOrSurcharges;
    }

    /**
     * @param DiscountsOrSurcharges|null $discountsOrSurcharges
     * @return ECF10
     */
    public function setDiscountsOrSurcharges(?DiscountsOrSurcharges $discountsOrSurcharges): self
    {
        $this->discountsOrSurcharges = $discountsOrSurcharges;
        return $this;
    }

    /**
     * @return Pagination|null
     */
    public function getPagination(): ?Pagination
    {
        return $this->pagination;
    }

    /**
     * @param Pagination|null $pagination
     * @return ECF10
     */
    public function setPagination(?Pagination $pagination): self
    {
        $this->pagination = $pagination;
        return $this;
    }

    /**
     * @return ReferenceInformation|null
     */
    public function getReferenceInformation(): ?ReferenceInformation
    {
        return $this->referenceInformation;
    }

    /**
     * @param ReferenceInformation|null $referenceInformation
     * @return ECF10
     */
    public function setReferenceInformation(?ReferenceInformation $referenceInformation): self
    {
        $this->referenceInformation = $referenceInformation;
        return $this;
    }

    /**
     * @return SignatureTimestamp|null
     */
    public function getSignatureTimestamp(): ?SignatureTimestamp
    {
        return $this->signatureTimestamp;
    }

    /**
     * @param SignatureTimestamp|null $signatureTimestamp
     * @return ECF10
     */
    public function setSignatureTimestamp(?SignatureTimestamp $signatureTimestamp): self
    {
        $this->signatureTimestamp = $signatureTimestamp;
        return $this;
    }

    /**
     * @return array
     */
    public function getAdditionalTaxRates(): array
    {
        return $this->additionalTaxRates;
    }

    /**
     * @param array $additionalTaxRates
     * @return ECF10
     */
    public function setAdditionalTaxRates(array $additionalTaxRates): self
    {
        $this->additionalTaxRates = $additionalTaxRates;
        return $this;
    }

    /**
     * @return DOMElement|null
     */
    public function getSignature(): ?DOMElement
    {
        return $this->signature;
    }

    /**
     * @param DOMElement $signature
     * @return ECF10
     */
    public function setSignature(DOMElement $signature): self
    {
        $this->signature = $signature;
        return $this;
    }


    #########################
    ##      LIBRARY        ##
    #########################

    // none.


    #########################
    ##       HELPER        ##
    #########################

    /**
     * Clean a string whitespace according to the ECF Spec, used for generating an original chain sequence
     * @param string $s
     * @return string
     */
    public static function cleanWhitespace(string $s): string
    {
        // Replace all non visible characters with a single space
        $s = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $s);

        // Trim whitespace at the beginning and end of the string
        $s = trim($s);

        // Collapse multiple spaces into a single space
        $s = preg_replace('/\s+/u', ' ', $s);

        return $s;
    }
}