<?php

namespace Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use DateTime;

use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable\AdditionalTax\TaxType;
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable\AdditionalTax\AdditionalTaxRate;
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable\AdditionalTax\SpecificConsumptionTaxAmount;
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable\AdditionalTax\AdValoremConsumptionTaxAmount;
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable\AdditionalTax\OtherAdditionalTaxes;


use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static AdditionalTax createFromDOMNode(DOMNode $node)
 */
class AdditionalTax extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "ImpuestoAdicional";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'taxType' => [
            'keywords'  => ['TipoImpuesto', 'taxType'],
            'class'     => TaxType::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'additionalTaxRate' => [
            'keywords'  => ['TasaImpuestoAdicional', 'additionalTaxRate'],
            'class'     => AdditionalTaxRate::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'specificConsumptionTaxAmount' => [
            'keywords'  => ['MontoImpuestoSelectivoConsumoEspecifico', 'specificConsumptionTaxAmount'],
            'class'     => SpecificConsumptionTaxAmount::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'adValoremConsumptionTaxAmount' => [
            'keywords'  => ['MontoImpuestoSelectivoConsumoAdvalorem', 'adValoremConsumptionTaxAmount'],
            'class'     => AdValoremConsumptionTaxAmount::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'otherAdditionalTaxes' => [
            'keywords'  => ['OtrosImpuestosAdicionales', 'otherAdditionalTaxes'],
            'class'     => OtherAdditionalTaxes::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
    ];

    private $value;


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var TaxType */
    protected $taxType;

    /** @var AdditionalTaxRate */
    protected $additionalTaxRate;

    /** @var SpecificConsumptionTaxAmount|null */
    protected $specificConsumptionTaxAmount;

    /** @var AdValoremConsumptionTaxAmount|null */
    protected $adValoremConsumptionTaxAmount;

    /** @var OtherAdditionalTaxes|null */
    protected $otherAdditionalTaxes;


    #########################
    ##     CONSTRUCTOR     ##
    #########################

    // constructor implemented in the CFDINode abstract class

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
                case TaxType::NODE_NAME:
                    $this->setTaxType(TaxType::createFromDOMNode($node));
                    break;
                case AdditionalTaxRate::NODE_NAME:
                    $this->setAdditionalTaxRate(AdditionalTaxRate::createFromDOMNode($node));
                    break;
                case SpecificConsumptionTaxAmount::NODE_NAME:
                    $this->setSpecificConsumptionTaxAmount(SpecificConsumptionTaxAmount::createFromDOMNode($node));
                    break;
                case AdValoremConsumptionTaxAmount::NODE_NAME:
                    $this->setAdValoremConsumptionTaxAmount(AdValoremConsumptionTaxAmount::createFromDOMNode($node));
                    break;
                case OtherAdditionalTaxes::NODE_NAME:
                    $this->setOtherAdditionalTaxes(OtherAdditionalTaxes::createFromDOMNode($node));
                    break;
            }
        }
    }


    #########################
    ## CFDI NODE TO DOM TRANSLATION
    #########################

    public function toDOMElement(DOMDocument $dom): DOMElement
    {
        $node = $dom->createElement(self::NODE_NAME);

        if ($this->taxType) {
            $node->appendChild($this->taxType->toDOMElement($dom));
        }

        if ($this->additionalTaxRate) {
            $node->appendChild($this->additionalTaxRate->toDOMElement($dom));
        }

        if ($this->specificConsumptionTaxAmount) {
            $node->appendChild($this->specificConsumptionTaxAmount->toDOMElement($dom));
        }

        if ($this->adValoremConsumptionTaxAmount) {
            $node->appendChild($this->adValoremConsumptionTaxAmount->toDOMElement($dom));
        }

        if ($this->otherAdditionalTaxes) {
            $node->appendChild($this->otherAdditionalTaxes->toDOMElement($dom));
        }

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



    #########################
    ##       CHILDREN      ##
    #########################

    public function getTaxType(): ?TaxType
    {
        return $this->taxType;
    }

    public function setTaxType(TaxType $taxType): self
    {
        $this->taxType = $taxType;
        return $this;
    }

    public function getAdditionalTaxRate(): ?AdditionalTaxRate
    {
        return $this->additionalTaxRate;
    }

    public function setAdditionalTaxRate(AdditionalTaxRate $additionalTaxRate): self
    {
        $this->additionalTaxRate = $additionalTaxRate;
        return $this;
    }

    public function getSpecificConsumptionTaxAmount(): ?SpecificConsumptionTaxAmount
    {
        return $this->specificConsumptionTaxAmount;
    }

    public function setSpecificConsumptionTaxAmount(?SpecificConsumptionTaxAmount $specificConsumptionTaxAmount): self
    {
        $this->specificConsumptionTaxAmount = $specificConsumptionTaxAmount;
        return $this;
    }

    public function getAdValoremConsumptionTaxAmount(): ?AdValoremConsumptionTaxAmount
    {
        return $this->adValoremConsumptionTaxAmount;
    }

    public function setAdValoremConsumptionTaxAmount(?AdValoremConsumptionTaxAmount $adValoremConsumptionTaxAmount): self
    {
        $this->adValoremConsumptionTaxAmount = $adValoremConsumptionTaxAmount;
        return $this;
    }

    public function getOtherAdditionalTaxes(): ?OtherAdditionalTaxes
    {
        return $this->otherAdditionalTaxes;
    }

    public function setOtherAdditionalTaxes(?OtherAdditionalTaxes $otherAdditionalTaxes): self
    {
        $this->otherAdditionalTaxes = $otherAdditionalTaxes;
        return $this;
    }
}