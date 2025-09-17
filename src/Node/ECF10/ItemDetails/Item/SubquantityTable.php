<?php

namespace Angle\ECF\Node\ECF10\ItemDetails\Item;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\Node\ECF10\ItemDetails\Item\SubquantityTable\SubquantityItem;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static SubquantityTable createFromDOMNode(DOMNode $node)
 */
class SubquantityTable extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "TablaSubcantidad";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'subquantityItem' => [
            'keywords'  => ['SubcantidadItem', 'subquantityItem'],
            'class'     => SubquantityItem::class,
            'type'      => ECFNode::CHILD_ARRAY,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var SubquantityItem[] */
    protected $subquantityItems = [];


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
                case SubquantityItem::NODE_NAME:
                    $subquantityItem = SubquantityItem::createFromDomNode($node);
                    $this->addSubquantityItem($subquantityItem);
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

        foreach ($this->subquantityItems as $item) {
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
     * @return SubquantityItem[]
     */
    public function getSubquantityItems(): ?array
    {
        return $this->subquantityItems;
    }

    /**
     * @param SubquantityItem[] $subquantityItems
     * @return SubquantityTable
     */
    public function setSubquantityItems(array $subquantityItems): self
    {
        $this->subquantityItems = $subquantityItems;
        return $this;
    }

    /**
     * @param SubquantityItem $subquantityItem
     * @return SubquantityTable
     */
    public function addSubquantityItem(SubquantityItem $subquantityItem): self
    {
        $this->subquantityItems[] = $subquantityItem;
        return $this;
    }
}