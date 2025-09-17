<?php

namespace Angle\ECF\Node\ECF10\ItemDetails;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;

use Angle\ECF\Node\ECF10\ItemDetails\Item\LineNumber;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ItemCodesTable;
use Angle\ECF\Node\ECF10\ItemDetails\Item\BillingIndicator;
use Angle\ECF\Node\ECF10\ItemDetails\Item\Retention;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ItemName;
use Angle\ECF\Node\ECF10\ItemDetails\Item\GoodOrServiceIndicator;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ItemDescription;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ItemQuantity;
use Angle\ECF\Node\ECF10\ItemDetails\Item\UnitOfMeasure;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ReferenceQuantity;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ReferenceUnit;
use Angle\ECF\Node\ECF10\ItemDetails\Item\SubquantityTable;
use Angle\ECF\Node\ECF10\ItemDetails\Item\AlcoholPercentage;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ReferenceUnitPrice;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ProductionDate;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ExpirationDate;
use Angle\ECF\Node\ECF10\ItemDetails\Item\UnitPrice;
use Angle\ECF\Node\ECF10\ItemDetails\Item\DiscountAmount;
use Angle\ECF\Node\ECF10\ItemDetails\Item\SubDiscountTable;
use Angle\ECF\Node\ECF10\ItemDetails\Item\SurchargeAmount;
use Angle\ECF\Node\ECF10\ItemDetails\Item\SubSurchargeTable;
use Angle\ECF\Node\ECF10\ItemDetails\Item\AdditionalTaxTable;
use Angle\ECF\Node\ECF10\ItemDetails\Item\OtherCurrencyDetails;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ItemAmount;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static Item createFromDOMNode(DOMNode $node)
 */
class Item extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "Item";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'lineNumber' => [
            'keywords' => ['NumeroLinea', 'lineNumber'],
            'class' => LineNumber::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'itemCodesTable' => [
            'keywords' => ['TablaCodigosItem', 'itemCodesTable'],
            'class' => ItemCodesTable::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'billingIndicator' => [
            'keywords' => ['IndicadorFacturacion', 'billingIndicator'],
            'class' => BillingIndicator::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'retention' => [
            'keywords' => ['Retencion', 'retention'],
            'class' => Retention::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'itemName' => [
            'keywords' => ['NombreItem', 'itemName'],
            'class' => ItemName::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'goodOrServiceIndicator' => [
            'keywords' => ['IndicadorBienoServicio', 'goodOrServiceIndicator'],
            'class' => GoodOrServiceIndicator::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'itemDescription' => [
            'keywords' => ['DescripcionItem', 'itemDescription'],
            'class' => ItemDescription::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'itemQuantity' => [
            'keywords' => ['CantidadItem', 'itemQuantity'],
            'class' => ItemQuantity::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'unitOfMeasure' => [
            'keywords' => ['UnidadMedida', 'unitOfMeasure'],
            'class' => UnitOfMeasure::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'referenceQuantity' => [
            'keywords' => ['CantidadReferencia', 'referenceQuantity'],
            'class' => ReferenceQuantity::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'referenceUnit' => [
            'keywords' => ['UnidadReferencia', 'referenceUnit'],
            'class' => ReferenceUnit::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'subquantityTable' => [
            'keywords' => ['TablaSubcantidad', 'subquantityTable'],
            'class' => SubquantityTable::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'alcoholPercentage' => [
            'keywords' => ['GradosAlcohol', 'alcoholPercentage'],
            'class' => AlcoholPercentage::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'referenceUnitPrice' => [
            'keywords' => ['PrecioUnitarioReferencia', 'referenceUnitPrice'],
            'class' => ReferenceUnitPrice::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'productionDate' => [
            'keywords' => ['FechaElaboracion', 'productionDate'],
            'class' => ProductionDate::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'expirationDate' => [
            'keywords' => ['FechaVencimientoItem', 'expirationDate'],
            'class' => ExpirationDate::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'unitPrice' => [
            'keywords' => ['PrecioUnitarioItem', 'unitPrice'],
            'class' => UnitPrice::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'discountAmount' => [
            'keywords' => ['DescuentoMonto', 'discountAmount'],
            'class' => DiscountAmount::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'subDiscountTable' => [
            'keywords' => ['TablaSubDescuento', 'subDiscountTable'],
            'class' => SubDiscountTable::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'surchargeAmount' => [
            'keywords' => ['RecargoMonto', 'surchargeAmount'],
            'class' => SurchargeAmount::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'subSurchargeTable' => [
            'keywords' => ['TablaSubRecargo', 'subSurchargeTable'],
            'class' => SubSurchargeTable::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'additionalTaxTable' => [
            'keywords' => ['TablaImpuestoAdicional', 'additionalTaxTable'],
            'class' => AdditionalTaxTable::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencyDetails' => [
            'keywords' => ['OtraMonedaDetalle', 'otherCurrencyDetails'],
            'class' => OtherCurrencyDetails::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'itemAmount' => [
            'keywords' => ['MontoItem', 'itemAmount'],
            'class' => ItemAmount::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
    ];

    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var LineNumber */
    protected $lineNumber;

    /** @var ItemCodesTable|null */
    protected $itemCodesTable;

    /** @var BillingIndicator */
    protected $billingIndicator;

    /** @var Retention|null */
    protected $retention;

    /** @var ItemName */
    protected $itemName;

    /** @var GoodOrServiceIndicator */
    protected $goodOrServiceIndicator;

    /** @var ItemDescription|null */
    protected $itemDescription;

    /** @var ItemQuantity */
    protected $itemQuantity;

    /** @var UnitOfMeasure|null */
    protected $unitOfMeasure;

    /** @var ReferenceQuantity|null */
    protected $referenceQuantity;

    /** @var ReferenceUnit|null */
    protected $referenceUnit;

    /** @var SubquantityTable|null */
    protected $subquantityTable;

    /** @var AlcoholPercentage|null */
    protected $alcoholPercentage;

    /** @var ReferenceUnitPrice|null */
    protected $referenceUnitPrice;

    /** @var ProductionDate|null */
    protected $productionDate;

    /** @var ExpirationDate|null */
    protected $expirationDate;

    /** @var Mining|null */
    protected $mining;

    /** @var UnitPrice */
    protected $unitPrice;

    /** @var DiscountAmount|null */
    protected $discountAmount;

    /** @var SubDiscountTable|null */
    protected $subDiscountTable;

    /** @var SurchargeAmount|null */
    protected $surchargeAmount;

    /** @var SubSurchargeTable|null */
    protected $subSurchargeTable;

    /** @var AdditionalTaxTable|null */
    protected $additionalTaxTable;

    /** @var OtherCurrencyDetails|null */
    protected $otherCurrencyDetails;

    /** @var ItemAmount */
    protected $itemAmount;


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
                continue;
            }

            switch ($node->localName) {
                case LineNumber::NODE_NAME:
                    $this->setLineNumber(LineNumber::createFromDOMNode($node));
                    break;
                case ItemCodesTable::NODE_NAME:
                    $this->setItemCodesTable(ItemCodesTable::createFromDOMNode($node));
                    break;
                case BillingIndicator::NODE_NAME:
                    $this->setBillingIndicator(BillingIndicator::createFromDOMNode($node));
                    break;
                case Retention::NODE_NAME:
                    $this->setRetention(Retention::createFromDOMNode($node));
                    break;
                case ItemName::NODE_NAME:
                    $this->setItemName(ItemName::createFromDOMNode($node));
                    break;
                case GoodOrServiceIndicator::NODE_NAME:
                    $this->setGoodOrServiceIndicator(GoodOrServiceIndicator::createFromDOMNode($node));
                    break;
                case ItemDescription::NODE_NAME:
                    $this->setItemDescription(ItemDescription::createFromDOMNode($node));
                    break;
                case ItemQuantity::NODE_NAME:
                    $this->setItemQuantity(ItemQuantity::createFromDOMNode($node));
                    break;
                case UnitOfMeasure::NODE_NAME:
                    $this->setUnitOfMeasure(UnitOfMeasure::createFromDOMNode($node));
                    break;
                case ReferenceQuantity::NODE_NAME:
                    $this->setReferenceQuantity(ReferenceQuantity::createFromDOMNode($node));
                    break;
                case ReferenceUnit::NODE_NAME:
                    $this->setReferenceUnit(ReferenceUnit::createFromDOMNode($node));
                    break;
                case SubquantityTable::NODE_NAME:
                    $this->setSubquantityTable(SubquantityTable::createFromDOMNode($node));
                    break;
                case AlcoholPercentage::NODE_NAME:
                    $this->setAlcoholPercentage(AlcoholPercentage::createFromDOMNode($node));
                    break;
                case ReferenceUnitPrice::NODE_NAME:
                    $this->setReferenceUnitPrice(ReferenceUnitPrice::createFromDOMNode($node));
                    break;
                case ProductionDate::NODE_NAME:
                    $this->setProductionDate(ProductionDate::createFromDOMNode($node));
                    break;
                case ExpirationDate::NODE_NAME:
                    $this->setExpirationDate(ExpirationDate::createFromDOMNode($node));
                    break;
                case UnitPrice::NODE_NAME:
                    $this->setUnitPrice(UnitPrice::createFromDOMNode($node));
                    break;
                case DiscountAmount::NODE_NAME:
                    $this->setDiscountAmount(DiscountAmount::createFromDOMNode($node));
                    break;
                case SubDiscountTable::NODE_NAME:
                    $this->setSubDiscountTable(SubDiscountTable::createFromDOMNode($node));
                    break;
                case SurchargeAmount::NODE_NAME:
                    $this->setSurchargeAmount(SurchargeAmount::createFromDOMNode($node));
                    break;
                case SubSurchargeTable::NODE_NAME:
                    $this->setSubSurchargeTable(SubSurchargeTable::createFromDOMNode($node));
                    break;
                case AdditionalTaxTable::NODE_NAME:
                    $this->setAdditionalTaxTable(AdditionalTaxTable::createFromDOMNode($node));
                    break;
                case OtherCurrencyDetails::NODE_NAME:
                    $this->setOtherCurrencyDetails(OtherCurrencyDetails::createFromDOMNode($node));
                    break;
                case ItemAmount::NODE_NAME:
                    $this->setItemAmount(ItemAmount::createFromDOMNode($node));
                    break;
            }
        }
    }


    #########################
    ## ECF NODE TO DOM TRANSLATION
    #########################

    public function toDOMElement(DOMDocument $dom): DOMElement
    {
        $node = $dom->createElement(self::NODE_NAME);

        // NOTE: The order of appendage is critical for XML schema validation.
        if ($this->lineNumber) $node->appendChild($this->lineNumber->toDOMElement($dom));
        if ($this->itemCodesTable) $node->appendChild($this->itemCodesTable->toDOMElement($dom));
        if ($this->billingIndicator) $node->appendChild($this->billingIndicator->toDOMElement($dom));
        if ($this->retention) $node->appendChild($this->retention->toDOMElement($dom));
        if ($this->itemName) $node->appendChild($this->itemName->toDOMElement($dom));
        if ($this->goodOrServiceIndicator) $node->appendChild($this->goodOrServiceIndicator->toDOMElement($dom));
        if ($this->itemDescription) $node->appendChild($this->itemDescription->toDOMElement($dom));
        if ($this->itemQuantity) $node->appendChild($this->itemQuantity->toDOMElement($dom));
        if ($this->unitOfMeasure) $node->appendChild($this->unitOfMeasure->toDOMElement($dom));
        if ($this->referenceQuantity) $node->appendChild($this->referenceQuantity->toDOMElement($dom));
        if ($this->referenceUnit) $node->appendChild($this->referenceUnit->toDOMElement($dom));
        if ($this->subquantityTable) $node->appendChild($this->subquantityTable->toDOMElement($dom));
        if ($this->alcoholPercentage) $node->appendChild($this->alcoholPercentage->toDOMElement($dom));
        if ($this->referenceUnitPrice) $node->appendChild($this->referenceUnitPrice->toDOMElement($dom));
        if ($this->productionDate) $node->appendChild($this->productionDate->toDOMElement($dom));
        if ($this->expirationDate) $node->appendChild($this->expirationDate->toDOMElement($dom));
        if ($this->mining) $node->appendChild($this->mining->toDOMElement($dom));
        if ($this->unitPrice) $node->appendChild($this->unitPrice->toDOMElement($dom));
        if ($this->discountAmount) $node->appendChild($this->discountAmount->toDOMElement($dom));
        if ($this->subDiscountTable) $node->appendChild($this->subDiscountTable->toDOMElement($dom));
        if ($this->surchargeAmount) $node->appendChild($this->surchargeAmount->toDOMElement($dom));
        if ($this->subSurchargeTable) $node->appendChild($this->subSurchargeTable->toDOMElement($dom));
        if ($this->additionalTaxTable) $node->appendChild($this->additionalTaxTable->toDOMElement($dom));
        if ($this->otherCurrencyDetails) $node->appendChild($this->otherCurrencyDetails->toDOMElement($dom));
        if ($this->itemAmount) $node->appendChild($this->itemAmount->toDOMElement($dom));

        return $node;
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
    ## GETTERS AND SETTERS ##
    #########################

    public function getLineNumber(): ?LineNumber
    {
        return $this->lineNumber;
    }

    public function setLineNumber(LineNumber $lineNumber): self
    {
        $this->lineNumber = $lineNumber;
        return $this;
    }

    public function getItemCodesTable(): ?ItemCodesTable
    {
        return $this->itemCodesTable;
    }

    public function setItemCodesTable(?ItemCodesTable $itemCodesTable): self
    {
        $this->itemCodesTable = $itemCodesTable;
        return $this;
    }

    public function getBillingIndicator(): ?BillingIndicator
    {
        return $this->billingIndicator;
    }

    public function setBillingIndicator(BillingIndicator $billingIndicator): self
    {
        $this->billingIndicator = $billingIndicator;
        return $this;
    }

    public function getRetention(): ?Retention
    {
        return $this->retention;
    }

    public function setRetention(?Retention $retention): self
    {
        $this->retention = $retention;
        return $this;
    }

    public function getItemName(): ?ItemName
    {
        return $this->itemName;
    }

    public function setItemName(ItemName $itemName): self
    {
        $this->itemName = $itemName;
        return $this;
    }

    public function getGoodOrServiceIndicator(): ?GoodOrServiceIndicator
    {
        return $this->goodOrServiceIndicator;
    }

    public function setGoodOrServiceIndicator(GoodOrServiceIndicator $goodOrServiceIndicator): self
    {
        $this->goodOrServiceIndicator = $goodOrServiceIndicator;
        return $this;
    }

    public function getItemDescription(): ?ItemDescription
    {
        return $this->itemDescription;
    }

    public function setItemDescription(?ItemDescription $itemDescription): self
    {
        $this->itemDescription = $itemDescription;
        return $this;
    }

    public function getItemQuantity(): ?ItemQuantity
    {
        return $this->itemQuantity;
    }

    public function setItemQuantity(ItemQuantity $itemQuantity): self
    {
        $this->itemQuantity = $itemQuantity;
        return $this;
    }

    public function getUnitOfMeasure(): ?UnitOfMeasure
    {
        return $this->unitOfMeasure;
    }

    public function setUnitOfMeasure(?UnitOfMeasure $unitOfMeasure): self
    {
        $this->unitOfMeasure = $unitOfMeasure;
        return $this;
    }

    public function getReferenceQuantity(): ?ReferenceQuantity
    {
        return $this->referenceQuantity;
    }

    public function setReferenceQuantity(?ReferenceQuantity $referenceQuantity): self
    {
        $this->referenceQuantity = $referenceQuantity;
        return $this;
    }

    public function getReferenceUnit(): ?ReferenceUnit
    {
        return $this->referenceUnit;
    }

    public function setReferenceUnit(?ReferenceUnit $referenceUnit): self
    {
        $this->referenceUnit = $referenceUnit;
        return $this;
    }

    public function getSubquantityTable(): ?SubquantityTable
    {
        return $this->subquantityTable;
    }

    public function setSubquantityTable(?SubquantityTable $subquantityTable): self
    {
        $this->subquantityTable = $subquantityTable;
        return $this;
    }

    public function getAlcoholPercentage(): ?AlcoholPercentage
    {
        return $this->alcoholPercentage;
    }

    public function setAlcoholPercentage(?AlcoholPercentage $alcoholPercentage): self
    {
        $this->alcoholPercentage = $alcoholPercentage;
        return $this;
    }

    public function getReferenceUnitPrice(): ?ReferenceUnitPrice
    {
        return $this->referenceUnitPrice;
    }

    public function setReferenceUnitPrice(?ReferenceUnitPrice $referenceUnitPrice): self
    {
        $this->referenceUnitPrice = $referenceUnitPrice;
        return $this;
    }

    public function getProductionDate(): ?ProductionDate
    {
        return $this->productionDate;
    }

    public function setProductionDate(?ProductionDate $productionDate): self
    {
        $this->productionDate = $productionDate;
        return $this;
    }

    public function getExpirationDate(): ?ExpirationDate
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(?ExpirationDate $expirationDate): self
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    public function getUnitPrice(): ?UnitPrice
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(UnitPrice $unitPrice): self
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    public function getDiscountAmount(): ?DiscountAmount
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(?DiscountAmount $discountAmount): self
    {
        $this->discountAmount = $discountAmount;
        return $this;
    }

    public function getSubDiscountTable(): ?SubDiscountTable
    {
        return $this->subDiscountTable;
    }

    public function setSubDiscountTable(?SubDiscountTable $subDiscountTable): self
    {
        $this->subDiscountTable = $subDiscountTable;
        return $this;
    }

    public function getSurchargeAmount(): ?SurchargeAmount
    {
        return $this->surchargeAmount;
    }

    public function setSurchargeAmount(?SurchargeAmount $surchargeAmount): self
    {
        $this->surchargeAmount = $surchargeAmount;
        return $this;
    }

    public function getSubSurchargeTable(): ?SubSurchargeTable
    {
        return $this->subSurchargeTable;
    }

    public function setSubSurchargeTable(?SubSurchargeTable $subSurchargeTable): self
    {
        $this->subSurchargeTable = $subSurchargeTable;
        return $this;
    }

    public function getAdditionalTaxTable(): ?AdditionalTaxTable
    {
        return $this->additionalTaxTable;
    }

    public function setAdditionalTaxTable(?AdditionalTaxTable $additionalTaxTable): self
    {
        $this->additionalTaxTable = $additionalTaxTable;
        return $this;
    }

    public function getOtherCurrencyDetails(): ?OtherCurrencyDetails
    {
        return $this->otherCurrencyDetails;
    }

    public function setOtherCurrencyDetails(?OtherCurrencyDetails $otherCurrencyDetails): self
    {
        $this->otherCurrencyDetails = $otherCurrencyDetails;
        return $this;
    }

    public function getItemAmount(): ?ItemAmount
    {
        return $this->itemAmount;
    }

    public function setItemAmount(ItemAmount $itemAmount): self
    {
        $this->itemAmount = $itemAmount;
        return $this;
    }
}