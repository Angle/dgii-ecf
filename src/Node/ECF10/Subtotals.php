<?php

namespace Angle\ECF\Node\ECF10;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\Node\ECF10\Subtotals\Subtotal;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static Subtotals createFromDOMNode(DOMNode $node)
 */
class Subtotals extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "Subtotales";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'subtotal' => [
            'keywords'  => ['Subtotal', 'subtotal'],
            'class'     => Subtotal::class,
            'type'      => ECFNode::CHILD_ARRAY,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var Subtotal[] */
    protected $subtotals = [];


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
                case Subtotal::NODE_NAME:
                    $subtotal = Subtotal::createFromDomNode($node);
                    $this->addSubtotal($subtotal);
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

        foreach ($this->subtotals as $item) {
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
     * @return Subtotal[]
     */
    public function getSubtotals(): ?array
    {
        return $this->subtotals;
    }

    /**
     * @param Subtotal[] $subtotals
     * @return Subtotals
     */
    public function setSubtotals(array $subtotals): self
    {
        $this->subtotals = $subtotals;
        return $this;
    }

    /**
     * @param Subtotal $subtotal
     * @return Subtotals
     */
    public function addSubtotal(Subtotal $subtotal): self
    {
        $this->subtotals[] = $subtotal;
        return $this;
    }
}