<?php

namespace Angle\ECF\Node\ECF10\DiscountsOrSurcharges;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;

use Angle\ECF\Node\ECF10\DiscountsOrSurcharges\DiscountOrSurcharge\LineNumber;
use Angle\ECF\Node\ECF10\DiscountsOrSurcharges\DiscountOrSurcharge\AdjustmentType;
use Angle\ECF\Node\ECF10\DiscountsOrSurcharges\DiscountOrSurcharge\Norm1007Indicator;
use Angle\ECF\Node\ECF10\DiscountsOrSurcharges\DiscountOrSurcharge\DiscountOrSurchargeDescription;
use Angle\ECF\Node\ECF10\DiscountsOrSurcharges\DiscountOrSurcharge\ValueType;
use Angle\ECF\Node\ECF10\DiscountsOrSurcharges\DiscountOrSurcharge\DiscountOrSurchargeValue;
use Angle\ECF\Node\ECF10\DiscountsOrSurcharges\DiscountOrSurcharge\DiscountOrSurchargeAmount;
use Angle\ECF\Node\ECF10\DiscountsOrSurcharges\DiscountOrSurcharge\DiscountOrSurchargeAmountOtherCurrency;
use Angle\ECF\Node\ECF10\DiscountsOrSurcharges\DiscountOrSurcharge\DiscountOrSurchargeBillingIndicator;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static DiscountOrSurcharge createFromDOMNode(DOMNode $node)
 */
class DiscountOrSurcharge extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "DescuentoORecargo";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'lineNumber' => [
            'keywords' => ['NumeroLinea', 'lineNumber'],
            'class' => LineNumber::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'adjustmentType' => [
            'keywords' => ['TipoAjuste', 'adjustmentType'],
            'class' => AdjustmentType::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'norm1007Indicator' => [
            'keywords' => ['IndicadorNorma1007', 'norm1007Indicator'],
            'class' => Norm1007Indicator::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'discountOrSurchargeDescription' => [
            'keywords' => ['DescripcionDescuentooRecargo', 'discountOrSurchargeDescription'],
            'class' => DiscountOrSurchargeDescription::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'valueType' => [
            'keywords' => ['TipoValor', 'valueType'],
            'class' => ValueType::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'discountOrSurchargeValue' => [
            'keywords' => ['ValorDescuentooRecargo', 'discountOrSurchargeValue'],
            'class' => DiscountOrSurchargeValue::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'discountOrSurchargeAmount' => [
            'keywords' => ['MontoDescuentooRecargo', 'discountOrSurchargeAmount'],
            'class' => DiscountOrSurchargeAmount::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'discountOrSurchargeAmountOtherCurrency' => [
            'keywords' => ['MontoDescuentooRecargoOtraMoneda', 'discountOrSurchargeAmountOtherCurrency'],
            'class' => DiscountOrSurchargeAmountOtherCurrency::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'discountOrSurchargeBillingIndicator' => [
            'keywords' => ['IndicadorFacturacionDescuentooRecargo', 'discountOrSurchargeBillingIndicator'],
            'class' => DiscountOrSurchargeBillingIndicator::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var LineNumber */
    protected $lineNumber;

    /** @var AdjustmentType */
    protected $adjustmentType;

    /** @var Norm1007Indicator */
    protected $norm1007Indicator;

    /** @var DiscountOrSurchargeDescription */
    protected $discountOrSurchargeDescription;

    /** @var ValueType */
    protected $valueType;

    /** @var DiscountOrSurchargeValue */
    protected $discountOrSurchargeValue;

    /** @var DiscountOrSurchargeAmount */
    protected $discountOrSurchargeAmount;

    /** @var DiscountOrSurchargeAmountOtherCurrency */
    protected $discountOrSurchargeAmountOtherCurrency;

    /** @var DiscountOrSurchargeBillingIndicator */
    protected $discountOrSurchargeBillingIndicator;



    #########################
    ##     CONSTRUCTOR     ##
    #########################

    public function setChildrenFromDOMNodes(array $children): void
    {
        foreach ($children as $node) {
            if ($node instanceof DOMText) continue;

            switch ($node->localName) {
                case 'NumeroLinea':
                    $this->setLineNumber(LineNumber::createFromDOMNode($node));
                    break;
                case 'TipoAjuste':
                    $this->setAdjustmentType(AdjustmentType::createFromDOMNode($node));
                    break;
                case 'IndicadorNorma1007':
                    $this->setNorm1007Indicator(Norm1007Indicator::createFromDOMNode($node));
                    break;
                case 'DescripcionDescuentooRecargo':
                    $this->setDiscountOrSurchargeDescription(DiscountOrSurchargeDescription::createFromDOMNode($node));
                    break;
                case 'TipoValor':
                    $this->setValueType(ValueType::createFromDOMNode($node));
                    break;
                case 'ValorDescuentooRecargo':
                    $this->setDiscountOrSurchargeValue(DiscountOrSurchargeValue::createFromDOMNode($node));
                    break;
                case 'MontoDescuentooRecargo':
                    $this->setDiscountOrSurchargeAmount(DiscountOrSurchargeAmount::createFromDOMNode($node));
                    break;
                case 'MontoDescuentooRecargoOtraMoneda':
                    $this->setDiscountOrSurchargeAmountOtherCurrency(DiscountOrSurchargeAmountOtherCurrency::createFromDOMNode($node));
                    break;
                case 'IndicadorFacturacionDescuentooRecargo':
                    $this->setDiscountOrSurchargeBillingIndicator(DiscountOrSurchargeBillingIndicator::createFromDOMNode($node));
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

        if ($this->lineNumber) {
            $node->appendChild($this->lineNumber->toDOMElement($dom));
        }
        if ($this->adjustmentType) {
            $node->appendChild($this->adjustmentType->toDOMElement($dom));
        }
        if ($this->norm1007Indicator) {
            $node->appendChild($this->norm1007Indicator->toDOMElement($dom));
        }
        if ($this->discountOrSurchargeDescription) {
            $node->appendChild($this->discountOrSurchargeDescription->toDOMElement($dom));
        }
        if ($this->valueType) {
            $node->appendChild($this->valueType->toDOMElement($dom));
        }
        if ($this->discountOrSurchargeValue) {
            $node->appendChild($this->discountOrSurchargeValue->toDOMElement($dom));
        }
        if ($this->discountOrSurchargeAmount) {
            $node->appendChild($this->discountOrSurchargeAmount->toDOMElement($dom));
        }
        if ($this->discountOrSurchargeAmountOtherCurrency) {
            $node->appendChild($this->discountOrSurchargeAmountOtherCurrency->toDOMElement($dom));
        }
        if ($this->discountOrSurchargeBillingIndicator) {
            $node->appendChild($this->discountOrSurchargeBillingIndicator->toDOMElement($dom));
        }

        return $node;
    }


    #########################
    ## VALIDATION
    #########################

    public function validate(): bool
    {
        // TODO: Implement validation based on the XSD rules.
        return true;
    }


    #########################
    ## GETTERS AND SETTERS ##
    #########################

    public function getLineNumber(): ?LineNumber
    {
        return $this->lineNumber;
    }
    public function setLineNumber(?LineNumber $lineNumber): self
    {
        $this->lineNumber = $lineNumber;
        return $this;
    }

    public function getAdjustmentType(): ?AdjustmentType
    {
        return $this->adjustmentType;
    }
    public function setAdjustmentType(?AdjustmentType $adjustmentType): self
    {
        $this->adjustmentType = $adjustmentType;
        return $this;
    }

    public function getNorm1007Indicator(): ?Norm1007Indicator
    {
        return $this->norm1007Indicator;
    }
    public function setNorm1007Indicator(?Norm1007Indicator $norm1007Indicator): self
    {
        $this->norm1007Indicator = $norm1007Indicator;
        return $this;
    }

    public function getDiscountOrSurchargeDescription(): ?DiscountOrSurchargeDescription
    {
        return $this->discountOrSurchargeDescription;
    }
    public function setDiscountOrSurchargeDescription(?DiscountOrSurchargeDescription $discountOrSurchargeDescription): self
    {
        $this->discountOrSurchargeDescription = $discountOrSurchargeDescription;
        return $this;
    }

    public function getValueType(): ?ValueType
    {
        return $this->valueType;
    }
    public function setValueType(?ValueType $valueType): self
    {
        $this->valueType = $valueType;
        return $this;
    }

    public function getDiscountOrSurchargeValue(): ?DiscountOrSurchargeValue
    {
        return $this->discountOrSurchargeValue;
    }
    public function setDiscountOrSurchargeValue(?DiscountOrSurchargeValue $discountOrSurchargeValue): self
    {
        $this->discountOrSurchargeValue = $discountOrSurchargeValue;
        return $this;
    }

    public function getDiscountOrSurchargeAmount(): ?DiscountOrSurchargeAmount
    {
        return $this->discountOrSurchargeAmount;
    }
    public function setDiscountOrSurchargeAmount(?DiscountOrSurchargeAmount $discountOrSurchargeAmount): self
    {
        $this->discountOrSurchargeAmount = $discountOrSurchargeAmount;
        return $this;
    }

    public function getDiscountOrSurchargeAmountOtherCurrency(): ?DiscountOrSurchargeAmountOtherCurrency
    {
        return $this->discountOrSurchargeAmountOtherCurrency;
    }
    public function setDiscountOrSurchargeAmountOtherCurrency(?DiscountOrSurchargeAmountOtherCurrency $discountOrSurchargeAmountOtherCurrency): self
    {
        $this->discountOrSurchargeAmountOtherCurrency = $discountOrSurchargeAmountOtherCurrency;
        return $this;
    }

    public function getDiscountOrSurchargeBillingIndicator(): ?DiscountOrSurchargeBillingIndicator
    {
        return $this->discountOrSurchargeBillingIndicator;
    }
    public function setDiscountOrSurchargeBillingIndicator(?DiscountOrSurchargeBillingIndicator $discountOrSurchargeBillingIndicator): self
    {
        $this->discountOrSurchargeBillingIndicator = $discountOrSurchargeBillingIndicator;
        return $this;
    }
}