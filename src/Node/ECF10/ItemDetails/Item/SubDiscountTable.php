<?php

namespace Angle\ECF\Node\ECF10\ItemDetails\Item;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\Node\ECF10\ItemDetails\Item\SubDiscountTable\SubDiscount;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static SubDiscountTable createFromDOMNode(DOMNode $node)
 */
class SubDiscountTable extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "TablaSubDescuento";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'subDiscount' => [
            'keywords'  => ['SubDescuento', 'subDiscount'],
            'class'     => SubDiscount::class,
            'type'      => ECFNode::CHILD_ARRAY,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var SubDiscount[] */
    protected $subDiscounts = [];


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
                case SubDiscount::NODE_NAME:
                    $subDiscount = SubDiscount::createFromDomNode($node);
                    $this->addSubDiscount($subDiscount);
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

        foreach ($this->subDiscounts as $item) {
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
     * @return SubDiscount[]
     */
    public function getSubDiscounts(): ?array
    {
        return $this->subDiscounts;
    }

    /**
     * @param SubDiscount[] $subDiscounts
     * @return SubDiscountTable
     */
    public function setSubDiscounts(array $subDiscounts): self
    {
        $this->subDiscounts = $subDiscounts;
        return $this;
    }

    /**
     * @param SubDiscount $subDiscount
     * @return SubDiscountTable
     */
    public function addSubDiscount(SubDiscount $subDiscount): self
    {
        $this->subDiscounts[] = $subDiscount;
        return $this;
    }
}