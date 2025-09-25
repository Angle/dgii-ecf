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
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable\AdditionalTax;
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable\AdditionalTax\AdditionalTaxRate;
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable\AdditionalTax\AdValoremConsumptionTaxAmount;
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable\AdditionalTax\SpecificConsumptionTaxAmount;
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable\AdditionalTax\TaxType as AdditionalTaxTaxType;
use Angle\ECF\Node\ECF10\ItemDetails\Item\AdditionalTaxTable\AdditionalTax\TaxType;
use Angle\ECF\Node\ECF10\ItemDetails\Item\BillingIndicator;
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

        $this->getHeader()->setTotals(new Totals([]));

        $itbis1TaxableAmount = 0;
        $itbis2TaxableAmount = 0;
        $itbis3TaxableAmount = 0;
        $exemptAmount = 0;
        $nonBilledAmount = 0;

        $additionalTaxes = [];
        foreach ($this->getItemDetails()->getItems() as $item) {
            switch ($item->getBillingIndicator()->getValue()) {
                case BillingIndicator::NOT_BILLED:
                    $nonBilledAmount = bcadd($nonBilledAmount, $item->getItemAmount()->getValue());
                    break;
                case BillingIndicator::ITBIS1:
                    $itbis1TaxableAmount = bcadd($itbis1TaxableAmount, $item->getItemAmount()->getValue());
                    break;
                case BillingIndicator::ITBIS2:
                    $itbis2TaxableAmount = bcadd($itbis2TaxableAmount, $item->getItemAmount()->getValue());
                    break;
                case BillingIndicator::ITBIS3:
                    $itbis3TaxableAmount = bcadd($itbis3TaxableAmount, $item->getItemAmount()->getValue());
                    break;
                case BillingIndicator::EXEMPT:
                    $exemptAmount = bcadd($exemptAmount, $item->getItemAmount()->getValue());
                    break;
            }

            //Process other taxes
            $additionalTaxesAmount = 0;
            //Create a total/additionalTax for each itemdetails/additionalTax
            if ($item->getAdditionalTaxTable() && $item->getAdditionalTaxTable()->getAdditionalTaxes()) {
                foreach ($item->getAdditionalTaxTable()->getAdditionalTaxes() as $at) {
                    //TODO: Send this logic to additionalTax maybe

                    $additionalTax = new AdditionalTax([]);
                    $taxType = $at->getTaxType()->getValue();
                    $additionalTax->setTaxType(AdditionalTaxTaxType::newWithValue($taxType));
                    $additionalTax->setAdditionalTaxRate(AdditionalTaxRate::newWithValue(AdditionalTaxType::getRate($taxType)));

                    $amount = 0;
                    //Process entries with tax type 06-22
                    if (AdditionalTaxType::isISCSpecific($taxType)) {
                        switch (AdditionalTaxType::getItemType($taxType)) {
                            //Process entries with tax type 06-18
                            case AdditionalTaxType::ALCOHOL:
                                //In case of UNIT 18 "Granel" we dont calculate this node.
                                if($item->getUnitOfMeasure()->getValue() == UnitType::BULK) {
                                    break;
                                }

                                //TasaImpuestoAdicional * GradosAlcohol * CantidadReferencia * Subcantidad * CantidadItem
                                //Ignore subcantidad for now
                                $amount = bcmul(AdditionalTaxType::getRate($taxType), $item->getAlcoholPercentage()->getValue());
                                $amount = bcmul($amount, $item->getReferenceQuantity()->getValue());
                                $amount = bcmul($amount, $item->getItemQuantity()->getValue());
                                $amount = bcround($amount,2);

                                $additionalTax->setSpecificConsumptionTaxAmount(SpecificConsumptionTaxAmount::newWithValue($amount));
                                $additionalTaxesAmount = bcadd($additionalTaxesAmount, $amount);
                                break;
                            //Process entries with tax type 19-22
                            case AdditionalTaxType::CIGARETTES:
                                //Cantidad Item * Cantidad Referencia * Tasa Impuesto Adicional
                                $amount = bcmul($item->getItemQuantity()->getValue(), $item->getReferenceQuantity()->getValue());
                                $amount = bcmul($amount, AdditionalTaxType::getRate($taxType));
                                $amount = bcround($amount,2);

                                $additionalTax->setSpecificConsumptionTaxAmount(SpecificConsumptionTaxAmount::newWithValue($amount));
                                $additionalTaxesAmount = bcadd($additionalTaxesAmount, $amount);
                                break;
                            default:
                                //Exception
                                break;
                        }
                    } elseif (AdditionalTaxType::isISCAdValorem($taxType)) { //Now we process 23-39
                        switch (AdditionalTaxType::getItemType($taxType)) {
                            //Process entries with tax type 23-35
                            case AdditionaltaxType::ALCOHOL:
                                //Special rules for UNIT "Granel"
                                if($item->getUnitOfMeasure()->getValue() == UnitType::BULK) {
                                    // PrecioUnitarioItem * 1.30 (30% of value) * TasaImpuestoAdicional * CantidadItem
                                    $amount = bcmul($item->getUnitPrice()->getValue(),1.30);
                                    $amount = bcmul($amount, AdditionalTaxType::getRate($taxType));
                                    $amount = bcmul($amount, $item->getItemQuantity()->getValue());

                                    $additionalTax->setAdValoremConsumptionTaxAmount(AdValoremConsumptionTaxAmount::newWithValue($amount));
                                    $additionalTaxesAmount = bcadd($additionalTaxesAmount, $amount);
                                } else {
                                    // ((PrecioUnitarioReferencia / (1+ ITBIS tasa 1)) - (ISPEspecificoMonto/(CantidadItem * CantidadReferencia))) / (1+tasa impuesto adicional especificado) * Cantidad Item * Cantidad Referencia * tasa impuesto adicional especificado
                                    //1. PrecioUnitarioReferencia / (1 + ITBIS tasa 1). This removes the itbis from the reference unit price
                                    $rupWithoutItbis = bcdiv($item->getReferenceUnitPrice()->getValue(), bcadd(1, CatalogTaxType::getRate(CatalogTaxType::ITBIS_1)));

                                    //1.1 Get ISCEspecificoMonto (this can be sent somewhere else)
                                    $iscSpecific = bcmul(AdditionalTaxType::getRate($taxType), $item->getAlcoholPercentage()->getValue());
                                    $iscSpecific = bcmul($iscSpecific, $item->getReferenceQuantity()->getValue());
                                    $iscSpecific = bcmul($iscSpecific, $item->getItemQuantity()->getValue());
                                    $iscSpecific = bcround($iscSpecific,2);

                                    //1.2 Get ISCEspecifico per unit
                                    $iscSpecificPerUnit = bcdiv($iscSpecific, bcmul($item->getItemQuantity()->getValue(), $item->getReferenceUnit()->getValue()));

                                    //2.  "1" - matching Specific ISC Amount Per Unit. This removes the iscSpecific from the reference unit price
                                    $rupWithoutItbisAndSpecific = bcsub($rupWithoutItbis, $iscSpecificPerUnit);

                                    //3.  "2" / (1 + isc ad valorem rate). This removes the iscAdValorem from the reference unit price
                                    $rupWithoutTaxes = bcdiv($rupWithoutItbisAndSpecific, bcadd(1, AdditionalTaxType::getRate($taxType)));

                                    //4.  "3" * isc ad valorem rate. This calculates the ad valorem tax per unit.
                                    $adValoremTaxPerUnit = bcmul($rupWithoutTaxes, AdditionalTaxType::getRate($taxType));

                                    //5.  "4" * ItemQuantity * referenceQuantity. This calculates the total ad valorem tax
                                    $amount = bcmul($adValoremTaxPerUnit, $item->getItemQuantity()->getValue());
                                    $amount = bcmul($amount, $item->getReferenceQuantity()->getValue());

                                    $additionalTax->setAdValoremConsumptionTaxAmount(AdValoremConsumptionTaxAmount::newWithValue($amount));
                                    $additionalTaxesAmount = bcadd($additionalTaxesAmount, $amount);
                                }

                                break;
                            //Process entries with tax type 36-39
                            case AdditionalTaxType::CIGARETTES:
                                // ((PrecioUnitarioReferencia / (1+ ITBIS tasa 1)) - TasaImpuestoAdicional) / (1+tasa impuesto adicional especificado) * Cantidad Item * Cantidad Referencia * tasa impuesto adicional especificado
                                //1. PrecioUnitarioReferencia / (1 + ITBIS tasa 1). This removes the itbis from the reference unit price
                                $rupWithoutItbis = bcdiv($item->getReferenceUnitPrice()->getValue(), bcadd(1, CatalogTaxType::getRate(CatalogTaxType::ITBIS_1)));

                                //2.  "1" - matching Specific ISC Rate. This removes the iscSpecific from the reference unit price
                                $rupWithoutItbisAndSpecific = bcsub($rupWithoutItbis, AdditionalTaxType::getRate(AdditionalTaxType::getMatchingSpecificTaxType($taxType)));

                                //3.  "2" / (1 + isc ad valorem rate). This removes the iscAdValorem from the reference unit price
                                $rupWithoutTaxes = bcdiv($rupWithoutItbisAndSpecific, bcadd(1, AdditionalTaxType::getRate($taxType)));

                                //4.  "3" * isc ad valorem rate. This calculates the ad valorem tax per unit.
                                $adValoremTaxPerUnit = bcmul($rupWithoutTaxes, AdditionalTaxType::getRate($taxType));

                                //5.  "4" * ItemQuantity * referenceQuantity. This calculates the total ad valorem tax
                                $amount = bcmul($adValoremTaxPerUnit, $item->getItemQuantity()->getValue());
                                $amount = bcmul($amount, $item->getReferenceQuantity()->getValue());

                                $additionalTax->setAdValoremConsumptionTaxAmount(AdValoremConsumptionTaxAmount::newWithValue($amount));
                                $additionalTaxesAmount = bcadd($additionalTaxesAmount, $amount);

                                break;
                            default:
                                break;

                        }
                    } else {
                        //TODO: Other taxes
                    }
                    //addi
                    //setiscE if applicable
                    //setiscAV if applicable
                    //set otherTax if applicable
                }
            }
        }
        //Create itbis1
        //create itbis2
        //Create itbis3
        //Create itbis1rate
        //Create itbis2rate
        //Create itbis3rate
        //CreateTotalTaxableItbis
        //CreateTOtalItbis
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
        if(!$ecfTypeValue) {
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