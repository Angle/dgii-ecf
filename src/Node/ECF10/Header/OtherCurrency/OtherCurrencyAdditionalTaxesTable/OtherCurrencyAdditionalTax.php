<?php

namespace Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyAdditionalTaxesTable;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use DateTime;

use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyAdditionalTaxesTable\OtherCurrencyAdditionalTax\OtherCurrencyTaxType;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyAdditionalTaxesTable\OtherCurrencyAdditionalTax\OtherCurrencyAdditionalTaxRate;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyAdditionalTaxesTable\OtherCurrencyAdditionalTax\OtherCurrencySpecificConsumptionTaxAmount;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyAdditionalTaxesTable\OtherCurrencyAdditionalTax\OtherCurrencyAdValoremConsumptionTaxAmount;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyAdditionalTaxesTable\OtherCurrencyAdditionalTax\OtherCurrencyOtherAdditionalTaxes;


use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static OtherCurrencyAdditionalTax createFromDOMNode(DOMNode $node)
 */
class OtherCurrencyAdditionalTax extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "ImpuestoAdicionalOtraMoneda";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'otherCurrencyTaxType' => [
            'keywords'  => ['TipoImpuestoOtraMoneda', 'otherCurrencyTaxType'],
            'class'     => OtherCurrencyTaxType::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencyAdditionalTaxRate' => [
            'keywords'  => ['TasaImpuestoAdicionalOtraMoneda', 'otherCurrencyAdditionalTaxRate'],
            'class'     => OtherCurrencyAdditionalTaxRate::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencySpecificConsumptionTaxAmount' => [
            'keywords'  => ['MontoImpuestoSelectivoConsumoEspecificoOtraMoneda', 'otherCurrencySpecificConsumptionTaxAmount'],
            'class'     => OtherCurrencySpecificConsumptionTaxAmount::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencyAdValoremConsumptionTaxAmount' => [
            'keywords'  => ['MontoImpuestoSelectivoConsumoAdvaloremOtraMoneda', 'otherCurrencyAdValoremConsumptionTaxAmount'],
            'class'     => OtherCurrencyAdValoremConsumptionTaxAmount::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencyOtherAdditionalTaxes' => [
            'keywords'  => ['OtrosImpuestosAdicionalesOtraMoneda', 'otherCurrencyOtherAdditionalTaxes'],
            'class'     => OtherCurrencyOtherAdditionalTaxes::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var OtherCurrencyTaxType */
    protected $otherCurrencyTaxType;

    /** @var OtherCurrencyAdditionalTaxRate */
    protected $otherCurrencyAdditionalTaxRate;

    /** @var OtherCurrencySpecificConsumptionTaxAmount|null */
    protected $otherCurrencySpecificConsumptionTaxAmount;

    /** @var OtherCurrencyAdValoremConsumptionTaxAmount|null */
    protected $otherCurrencyAdValoremConsumptionTaxAmount;

    /** @var OtherCurrencyOtherAdditionalTaxes|null */
    protected $otherCurrencyOtherAdditionalTaxes;


    #########################
    ##     CONSTRUCTOR     ##
    #########################

    // constructor implemented in the ECFNode abstract class

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
                case OtherCurrencyTaxType::NODE_NAME:
                    $this->setOtherCurrencyTaxType(OtherCurrencyTaxType::createFromDOMNode($node));
                    break;
                case OtherCurrencyAdditionalTaxRate::NODE_NAME:
                    $this->setOtherCurrencyAdditionalTaxRate(OtherCurrencyAdditionalTaxRate::createFromDOMNode($node));
                    break;
                case OtherCurrencySpecificConsumptionTaxAmount::NODE_NAME:
                    $this->setOtherCurrencySpecificConsumptionTaxAmount(OtherCurrencySpecificConsumptionTaxAmount::createFromDOMNode($node));
                    break;
                case OtherCurrencyAdValoremConsumptionTaxAmount::NODE_NAME:
                    $this->setOtherCurrencyAdValoremConsumptionTaxAmount(OtherCurrencyAdValoremConsumptionTaxAmount::createFromDOMNode($node));
                    break;
                case OtherCurrencyOtherAdditionalTaxes::NODE_NAME:
                    $this->setOtherCurrencyOtherAdditionalTaxes(OtherCurrencyOtherAdditionalTaxes::createFromDOMNode($node));
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

        if ($this->otherCurrencyTaxType) {
            $node->appendChild($this->otherCurrencyTaxType->toDOMElement($dom));
        }

        if ($this->otherCurrencyAdditionalTaxRate) {
            $node->appendChild($this->otherCurrencyAdditionalTaxRate->toDOMElement($dom));
        }

        if ($this->otherCurrencySpecificConsumptionTaxAmount) {
            $node->appendChild($this->otherCurrencySpecificConsumptionTaxAmount->toDOMElement($dom));
        }

        if ($this->otherCurrencyAdValoremConsumptionTaxAmount) {
            $node->appendChild($this->otherCurrencyAdValoremConsumptionTaxAmount->toDOMElement($dom));
        }

        if ($this->otherCurrencyOtherAdditionalTaxes) {
            $node->appendChild($this->otherCurrencyOtherAdditionalTaxes->toDOMElement($dom));
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

    public function getOtherCurrencyTaxType(): ?OtherCurrencyTaxType
    {
        return $this->otherCurrencyTaxType;
    }

    public function setOtherCurrencyTaxType(OtherCurrencyTaxType $otherCurrencyTaxType): self
    {
        $this->otherCurrencyTaxType = $otherCurrencyTaxType;
        return $this;
    }

    public function getOtherCurrencyAdditionalTaxRate(): ?OtherCurrencyAdditionalTaxRate
    {
        return $this->otherCurrencyAdditionalTaxRate;
    }

    public function setOtherCurrencyAdditionalTaxRate(OtherCurrencyAdditionalTaxRate $otherCurrencyAdditionalTaxRate): self
    {
        $this->otherCurrencyAdditionalTaxRate = $otherCurrencyAdditionalTaxRate;
        return $this;
    }

    public function getOtherCurrencySpecificConsumptionTaxAmount(): ?OtherCurrencySpecificConsumptionTaxAmount
    {
        return $this->otherCurrencySpecificConsumptionTaxAmount;
    }

    public function setOtherCurrencySpecificConsumptionTaxAmount(?OtherCurrencySpecificConsumptionTaxAmount $otherCurrencySpecificConsumptionTaxAmount): self
    {
        $this->otherCurrencySpecificConsumptionTaxAmount = $otherCurrencySpecificConsumptionTaxAmount;
        return $this;
    }

    public function getOtherCurrencyAdValoremConsumptionTaxAmount(): ?OtherCurrencyAdValoremConsumptionTaxAmount
    {
        return $this->otherCurrencyAdValoremConsumptionTaxAmount;
    }

    public function setOtherCurrencyAdValoremConsumptionTaxAmount(?OtherCurrencyAdValoremConsumptionTaxAmount $otherCurrencyAdValoremConsumptionTaxAmount): self
    {
        $this->otherCurrencyAdValoremConsumptionTaxAmount = $otherCurrencyAdValoremConsumptionTaxAmount;
        return $this;
    }

    public function getOtherCurrencyOtherAdditionalTaxes(): ?OtherCurrencyOtherAdditionalTaxes
    {
        return $this->otherCurrencyOtherAdditionalTaxes;
    }

    public function setOtherCurrencyOtherAdditionalTaxes(?OtherCurrencyOtherAdditionalTaxes $otherCurrencyOtherAdditionalTaxes): self
    {
        $this->otherCurrencyOtherAdditionalTaxes = $otherCurrencyOtherAdditionalTaxes;
        return $this;
    }
}