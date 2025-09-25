<?php

namespace Angle\ECF\Node\ECF10\ItemDetails\Item\AdditionalTaxTable;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\Node\ECF10\ItemDetails\Item\AdditionalTaxTable\AdditionalTax\TaxType;
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
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var TaxType */
    protected $taxType;


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
                case TaxType::NODE_NAME:
                    $this->setTaxType(TaxType::createFromDOMNode($node));
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

        if ($this->taxType) {
            $node->appendChild($this->taxType->toDOMElement($dom));
        }

        return $node;
    }


    #########################
    ## VALIDATION
    #########################

    public function validate(): bool
    {
        // TODO: implement the full set of validation
        return true;
    }


    #########################
    ## GETTERS AND SETTERS ##
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
}