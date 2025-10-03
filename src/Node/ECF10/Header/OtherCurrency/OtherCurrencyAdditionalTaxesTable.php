<?php

namespace Angle\ECF\Node\ECF10\Header\OtherCurrency;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyAdditionalTaxesTable\OtherCurrencyAdditionalTax;
use DateTime;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static OtherCurrencyAdditionalTaxesTable createFromDOMNode(DOMNode $node)
 */
class OtherCurrencyAdditionalTaxesTable extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "ImpuestosAdicionalesOtraMoneda";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'otherCurrencyAdditionalTax' => [
            'keywords'  => ['ImpuestoAdicionalOtraMoneda', 'otherCurrencyAdditionalTax'],
            'class'     => OtherCurrencyAdditionalTax::class,
            'type'      => ECFNode::CHILD_ARRAY,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var OtherCurrencyAdditionalTax[] otherCurrencyAdditionalTax */
    protected $otherCurrencyAdditionalTaxes = [];

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
                case OtherCurrencyAdditionalTax::NODE_NAME:
                    $otherCurrencyAdditionalTaxes = OtherCurrencyAdditionalTax::createFromDomNode($node);
                    $this->addOtherCurrencyAdditionalTax($otherCurrencyAdditionalTaxes);
                    break;
                default:
                    throw new ECFException(sprintf("Unknown children node '%s'", $node->nodeName));
            }
        }
    }


    #########################
    ## ECF NODE TO DOM TRANSLATION
    #########################

    public function toDOMElement(DOMDocument $dom): DOMElement
    {
        $node = $dom->createElement(self::NODE_NAME);

        // Additional Tax node (array)
        foreach ($this->otherCurrencyAdditionalTaxes as $otherCurrencyAdditionalTax) {
            $otherCurrencyAdditionalTaxNode = $otherCurrencyAdditionalTax->toDOMElement($dom);
            $node->appendChild($otherCurrencyAdditionalTaxNode);
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

    /**
     * @return OtherCurrencyAdditionalTax[]
     */
    public function getOtherCurrencyAdditionalTaxes(): ?array
    {
        return $this->otherCurrencyAdditionalTaxes;
    }

    /**
     * @param OtherCurrencyAdditionalTax[] $otherCurrencyAdditionalTaxes
     * @return OtherCurrencyAdditionalTaxesTable
     */
    public function setOtherCurrencyAdditionalTaxes(array $otherCurrencyAdditionalTaxes): self
    {
        $this->otherCurrencyAdditionalTaxes = $otherCurrencyAdditionalTaxes;
        return $this;
    }

    /**
     * @param OtherCurrencyAdditionalTax $otherCurrencyAdditionalTax
     * @return OtherCurrencyAdditionalTaxesTable
     */
    public function addOtherCurrencyAdditionalTax(OtherCurrencyAdditionalTax $otherCurrencyAdditionalTax): self
    {
        $this->otherCurrencyAdditionalTaxes[] = $otherCurrencyAdditionalTax;
        return $this;
    }
}