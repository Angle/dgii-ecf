<?php

namespace Angle\ECF\Node\ECF10\ItemDetails\Item;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;

use Angle\ECF\Node\ECF10\ItemDetails\Item\ItemCodesTable\ItemCode;

use DateTime;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static ItemCodesTable createFromDOMNode(DOMNode $node)
 */
class ItemCodesTable extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "TablaCodigosItem";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'itemCode' => [
            'keywords'  => ['CodigosItem', 'itemCode'],
            'class'     => ItemCode::class,
            'type'      => ECFNode::CHILD_ARRAY,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var ItemCode[] */
    protected $itemCodes = [];


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
                case ItemCode::NODE_NAME:
                    $itemCode = ItemCode::createFromDomNode($node);
                    $this->addItemCode($itemCode);
                    break;
                default:
                    // might be a comment or something else to ignore
            }
        }
    }


    #########################
    ## CFDI NODE TO DOM TRANSLATION
    #########################

    public function toDOMElement(DOMDocument $dom): DOMElement
    {
        $node = $dom->createElement(self::NODE_NAME);

        // ItemCode node (array)
        foreach ($this->itemCodes as $itemCode) {
            $itemCodeNode = $itemCode->toDOMElement($dom);
            $node->appendChild($itemCodeNode);
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
     * @return ItemCode[]
     */
    public function getItemCodes(): ?array
    {
        return $this->itemCodes;
    }

    /**
     * @param ItemCode[] $itemCodes
     * @return ItemCodesTable
     */
    public function setItemCodes(array $itemCodes): self
    {
        $this->itemCodes = $itemCodes;
        return $this;
    }

    /**
     * @param ItemCode $itemCode
     * @return ItemCodesTable
     */
    public function addItemCode(ItemCode $itemCode): self
    {
        $this->itemCodes[] = $itemCode;
        return $this;
    }
}