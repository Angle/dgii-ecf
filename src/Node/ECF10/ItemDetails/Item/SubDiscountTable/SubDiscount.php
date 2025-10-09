<?php

namespace Angle\ECF\Node\ECF10\ItemDetails\Item\SubDiscountTable;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;

use Angle\ECF\Node\ECF10\ItemDetails\Item\SubDiscountTable\SubDiscount\DiscountPercentage;
use Angle\ECF\Node\ECF10\ItemDetails\Item\SubDiscountTable\SubDiscount\DiscountAmount;
use Angle\ECF\Node\ECF10\ItemDetails\Item\SubDiscountTable\SubDiscount\SubDiscountType;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static SubDiscount createFromDOMNode(DOMNode $node)
 */
class SubDiscount extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "SubDescuento";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'subDiscountType' => [
            'keywords'  => ['TipoSubDescuento', 'subDiscountType'],
            'class'     => SubDiscountType::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'discountPercentage' => [
            'keywords'  => ['SubDescuentoPorcentaje', 'discountPercentage'],
            'class'     => DiscountPercentage::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'discountAmount' => [
            'keywords'  => ['MontoSubDescuento', 'discountAmount'],
            'class'     => DiscountAmount::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var SubDiscountType */
    protected $subDiscountType;

    /** @var DiscountPercentage|null */
    protected $discountPercentage;

    /** @var DiscountAmount|null */
    protected $discountAmount;


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
                case SubDiscountType::NODE_NAME:
                    $this->setSubDiscountType(SubDiscountType::createFromDOMNode($node));
                    break;
                case DiscountPercentage::NODE_NAME:
                    $this->setDiscountPercentage(DiscountPercentage::createFromDOMNode($node));
                    break;
                case DiscountAmount::NODE_NAME:
                    $this->setDiscountAmount(DiscountAmount::createFromDOMNode($node));
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

        if ($this->subDiscountType) {
            $node->appendChild($this->subDiscountType->toDOMElement($dom));
        }

        if ($this->discountPercentage) {
            $node->appendChild($this->discountPercentage->toDOMElement($dom));
        }

        if ($this->discountAmount) {
            $node->appendChild($this->discountAmount->toDOMElement($dom));
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

    public function getSubDiscountType(): ?SubDiscountType
    {
        return $this->subDiscountType;
    }

    public function setSubDiscountType(SubDiscountType $subDiscountType): self
    {
        $this->subDiscountType = $subDiscountType;
        return $this;
    }

    public function getDiscountPercentage(): ?DiscountPercentage
    {
        return $this->discountPercentage;
    }

    public function setDiscountPercentage(?DiscountPercentage $discountPercentage): self
    {
        $this->discountPercentage = $discountPercentage;
        return $this;
    }

    public function getDiscountAmount(): ?DiscountAmount
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(?DiscountAmount $discountAmount): self
    {
        $this->discountAmount = $discountAmount;
        return $this;
    }
}