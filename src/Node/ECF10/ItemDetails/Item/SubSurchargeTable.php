<?php

namespace Angle\ECF\Node\ECF10\ItemDetails\Item;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\Node\ECF10\ItemDetails\Item\SubSurchargeTable\SubSurcharge;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static SubSurchargeTable createFromDOMNode(DOMNode $node)
 */
class SubSurchargeTable extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "TablaSubRecargo";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'subSurcharge' => [
            'keywords'  => ['SubRecargo', 'subSurcharge'],
            'class'     => SubSurcharge::class,
            'type'      => ECFNode::CHILD_ARRAY,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var SubSurcharge[] */
    protected $subSurcharges = [];


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
                case SubSurcharge::NODE_NAME:
                    $subSurcharge = SubSurcharge::createFromDomNode($node);
                    $this->addSubSurcharge($subSurcharge);
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

        foreach ($this->subSurcharges as $item) {
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
     * @return SubSurcharge[]
     */
    public function getSubSurcharges(): ?array
    {
        return $this->subSurcharges;
    }

    /**
     * @param SubSurcharge[] $subSurcharges
     * @return SubSurchargeTable
     */
    public function setSubSurcharges(array $subSurcharges): self
    {
        $this->subSurcharges = $subSurcharges;
        return $this;
    }

    /**
     * @param SubSurcharge $subSurcharge
     * @return SubSurchargeTable
     */
    public function addSubSurcharge(SubSurcharge $subSurcharge): self
    {
        $this->subSurcharges[] = $subSurcharge;
        return $this;
    }
}