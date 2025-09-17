<?php

namespace Angle\ECF\Node\ECF10;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\Node\ECF10\ItemDetails\Item;
use DateTime;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static ItemDetails createFromDOMNode(DOMNode $node)
 */
class ItemDetails extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "DetallesItems";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'item' => [
            'keywords'  => ['Item', 'item'],
            'class'     => Item::class,
            'type'      => ECFNode::CHILD_ARRAY,
        ],
    ];



    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var Item[] $items */
    protected $items = [];

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
                case Item::NODE_NAME:
                    $item = Item::createFromDomNode($node);
                    $this->addItem($item);
                    break;
                default:
                    throw new ECFException(sprintf("Unknown children node '%s'", $node->nodeName));
            }
        }
    }


    #########################
    ## CFDI NODE TO DOM TRANSLATION
    #########################

    public function toDOMElement(DOMDocument $dom): DOMElement
    {
        $node = $dom->createElement(self::NODE_NAME);

        // Payment Methods node (array)
        foreach ($this->items as $item) {
            $itemNode = $item->toDOMElement($dom);
            $node->appendChild($itemNode);
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
     * @return Item[]
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * @param Item[] $items
     * @return ItemDetails
     */
    public function setItems(array $items): self
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @param Item $item
     * @return ItemDetails
     */
    public function addItem(Item $item): self
    {
        $this->items[] = $item;
        return $this;
    }
}