<?php

namespace Angle\ECF\Node\ECF10\ItemDetails\Item;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\Node\ECF10\ItemDetails\Item\AdditionalTaxTable\AdditionalTax;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static AdditionalTaxTable createFromDOMNode(DOMNode $node)
 */
class AdditionalTaxTable extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "TablaImpuestoAdicional";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'additionalTax' => [
            'keywords'  => ['ImpuestoAdicional', 'additionalTax'],
            'class'     => AdditionalTax::class,
            'type'      => ECFNode::CHILD_ARRAY,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var AdditionalTax[] */
    protected $additionalTaxes = [];


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
                case AdditionalTax::NODE_NAME:
                    $additionalTax = AdditionalTax::createFromDomNode($node);
                    $this->addAdditionalTax($additionalTax);
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

        foreach ($this->additionalTaxes as $item) {
            $node->appendChild($item->toDOMElement($dom));
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

    /**
     * @return AdditionalTax[]
     */
    public function getAdditionalTaxes(): ?array
    {
        return $this->additionalTaxes;
    }

    /**
     * @param AdditionalTax[] $additionalTaxes
     * @return AdditionalTaxTable
     */
    public function setAdditionalTaxes(array $additionalTaxes): self
    {
        $this->additionalTaxes = $additionalTaxes;
        return $this;
    }

    /**
     * @param AdditionalTax $additionalTax
     * @return AdditionalTaxTable
     */
    public function addAdditionalTax(AdditionalTax $additionalTax): self
    {
        $this->additionalTaxes[] = $additionalTax;
        return $this;
    }
}