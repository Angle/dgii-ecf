<?php

namespace Angle\ECF\Node\ECF10;

use Angle\ECF\Catalog\AdditionalTaxType;
use Angle\ECF\Catalog\ECFType;
use Angle\ECF\Catalog\TaxType as CatalogTaxType;
use Angle\ECF\Catalog\UnitType;
use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\ECFInterface;
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
use Angle\ECF\Utility\Math;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

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
        //TODO: Signature
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

    // TODO: Signature


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
                //TODO: Signature
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

        //TODO: Signature

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

                    $rate = AdditionalTaxType::getRate($taxType);
                    //Allow to manually inject rate for older invoices if we dont want to use the preset values
                    if(array_key_exists($taxType,$this->additionalTaxRates)) {
                        $rate = $this->additionalTaxRates[$taxType];
                    }
                    $additionalTax->setAdditionalTaxRate(AdditionalTaxRate::newWithValue($rate));

                    //Process entries with tax type 06-22
                    if (AdditionalTaxType::isISCSpecific($taxType)) {
                        print_r("Adding additional tax");

                        $amount = $item->getIscSpecific($this->additionalTaxRates);
                        if($amount == null) {
                            continue;
                        }

                        $additionalTax->setSpecificConsumptionTaxAmount(SpecificConsumptionTaxAmount::newWithValue($amount));
                        $additionalTaxesAmount = Math::add($additionalTaxesAmount, $amount);

                    } elseif (AdditionalTaxType::isISCAdValorem($taxType)) { //Now we process 23-39
                        $amount = $item->getIscAdValorem($this->additionalTaxRates);
                        if($amount == null) {
                            continue;
                        }

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
            $itbis1Total = Math::mul($itbis1TaxableAmount, CatalogTaxType::getRate(CatalogTaxType::ITBIS_1));
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

            $totals->setAdditionalTaxAmount(AdditionalTaxAmount::newWithValue($additionalTaxesAmount));
        }

        //Finally we set the total amount (MontoGravadoTotal + Monto Exento + Total ITBIS + Monto del impuesto adicional)
        $totalAmount = Math::add($totalTaxableAmount, $exemptAmount);
        $totalAmount = Math::add($totalAmount, $totalItbisAmount);
        $totalAmount = Math::add($totalAmount, $additionalTaxesAmount);
        $totalAmount = Math::round($totalAmount,2);
        $totals->setTotalAmount(TotalAmount::newWithValue($totalAmount));

        $this->getHeader()->setTotals($totals);
        //Create total amount

        //Todo: retention total amounts (ITBISRetenido,ISRRetenido, ITBISPercepcion, ISRPercepcion)
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