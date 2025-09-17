<?php

namespace Angle\ECF\Node\ECF10\ItemDetails\Item\SubquantityTable;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;

use Angle\ECF\Node\ECF10\ItemDetails\Item\SubquantityTable\SubquantityItem\Subquantity;
use Angle\ECF\Node\ECF10\ItemDetails\Item\SubquantityTable\SubquantityItem\SubquantityCode;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static SubquantityItem createFromDOMNode(DOMNode $node)
 */
class SubquantityItem extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "SubcantidadItem";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'subquantity' => [
            'keywords'  => ['Subcantidad', 'subquantity'],
            'class'     => Subquantity::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'subquantityCode' => [
            'keywords'  => ['CodigoSubcantidad', 'subquantityCode'],
            'class'     => SubquantityCode::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var Subquantity|null */
    protected $subquantity;

    /** @var SubquantityCode|null */
    protected $subquantityCode;


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
                case Subquantity::NODE_NAME:
                    $this->setSubquantity(Subquantity::createFromDOMNode($node));
                    break;
                case SubquantityCode::NODE_NAME:
                    $this->setSubquantityCode(SubquantityCode::createFromDOMNode($node));
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

        if ($this->subquantity) {
            $node->appendChild($this->subquantity->toDOMElement($dom));
        }

        if ($this->subquantityCode) {
            $node->appendChild($this->subquantityCode->toDOMElement($dom));
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

    public function getSubquantity(): ?Subquantity
    {
        return $this->subquantity;
    }

    public function setSubquantity(?Subquantity $subquantity): self
    {
        $this->subquantity = $subquantity;
        return $this;
    }

    public function getSubquantityCode(): ?SubquantityCode
    {
        return $this->subquantityCode;
    }

    public function setSubquantityCode(?SubquantityCode $subquantityCode): self
    {
        $this->subquantityCode = $subquantityCode;
        return $this;
    }
}