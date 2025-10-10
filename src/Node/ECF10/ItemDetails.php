<?php

namespace Angle\ECF\Node\ECF10;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\Node\ECF10\ItemDetails\Item;
use Angle\ECF\Utility\Math;
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
    ## ECF NODE TO DOM TRANSLATION
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
    ##   SPECIAL METHODS   ##
    #########################


    public function getTaxableAmount(?string $indicator = null, int $lineFrom = 1, ?int $lineTo = null) {
        if($lineTo == null) $lineTo = count($this->items);

        $itemsByLineNumber = [];
        foreach($this->items as $i) {
            $itemsByLineNumber[$i->getLineNumber()->getValue()] = $i;
        }

        $amount = '0';
        for($i = $lineFrom; $i <= $lineTo; $i++) {
            if($indicator == null || $itemsByLineNumber[$i]->getBillingIndicator()->getValue() == $indicator) {
                $amount = Math::add($amount, $itemsByLineNumber[$i]->getItemAmount()->getValue());
            }
        }

        return $amount;
    }

    public function getTaxableAmountOtherCurrency(?string $indicator = null, int $lineFrom = 1, ?int $lineTo = null) {
        if($lineTo == null) $lineTo = count($this->items);

        $itemsByLineNumber = [];
        foreach($this->items as $i) {
            $itemsByLineNumber[$i->getLineNumber()->getValue()] = $i;
        }

        $amount = '0';
        for($i = $lineFrom; $i <= $lineTo; $i++) {
            if($indicator == null || $itemsByLineNumber[$i]->getBillingIndicator()->getValue() == $indicator) {
                $amount = Math::add($amount, $itemsByLineNumber[$i]->getOtherCurrencyDetails()->getOtherCurrencyItemAmount()->getValue());
            }
        }

        return $amount;
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