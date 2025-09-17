<?php

namespace Angle\ECF\Node\ECF10\ItemDetails\Item\SubSurchargeTable;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;

use Angle\ECF\Node\ECF10\ItemDetails\Item\SubSurchargeTable\SubSurcharge\SurchargeType;
use Angle\ECF\Node\ECF10\ItemDetails\Item\SubSurchargeTable\SubSurcharge\SurchargePercentage;
use Angle\ECF\Node\ECF10\ItemDetails\Item\SubSurchargeTable\SubSurcharge\SurchargeAmount;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static SubSurcharge createFromDOMNode(DOMNode $node)
 */
class SubSurcharge extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "SubRecargo";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'surchargeType' => [
            'keywords'  => ['TipoSubRecargo', 'surchargeType'],
            'class'     => SurchargeType::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'surchargePercentage' => [
            'keywords'  => ['SubRecargoPorcentaje', 'surchargePercentage'],
            'class'     => SurchargePercentage::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'surchargeAmount' => [
            'keywords'  => ['MontoSubRecargo', 'surchargeAmount'],
            'class'     => SurchargeAmount::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var SurchargeType */
    protected $surchargeType;

    /** @var SurchargePercentage|null */
    protected $surchargePercentage;

    /** @var SurchargeAmount|null */
    protected $surchargeAmount;


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
                case SurchargeType::NODE_NAME:
                    $this->setSurchargeType(SurchargeType::createFromDOMNode($node));
                    break;
                case SurchargePercentage::NODE_NAME:
                    $this->setSurchargePercentage(SurchargePercentage::createFromDOMNode($node));
                    break;
                case SurchargeAmount::NODE_NAME:
                    $this->setSurchargeAmount(SurchargeAmount::createFromDOMNode($node));
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

        if ($this->surchargeType) {
            $node->appendChild($this->surchargeType->toDOMElement($dom));
        }

        if ($this->surchargePercentage) {
            $node->appendChild($this->surchargePercentage->toDOMElement($dom));
        }

        if ($this->surchargeAmount) {
            $node->appendChild($this->surchargeAmount->toDOMElement($dom));
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

    public function getSurchargeType(): ?SurchargeType
    {
        return $this->surchargeType;
    }

    public function setSurchargeType(SurchargeType $surchargeType): self
    {
        $this->surchargeType = $surchargeType;
        return $this;
    }

    public function getSurchargePercentage(): ?SurchargePercentage
    {
        return $this->surchargePercentage;
    }

    public function setSurchargePercentage(?SurchargePercentage $surchargePercentage): self
    {
        $this->surchargePercentage = $surchargePercentage;
        return $this;
    }

    public function getSurchargeAmount(): ?SurchargeAmount
    {
        return $this->surchargeAmount;
    }

    public function setSurchargeAmount(?SurchargeAmount $surchargeAmount): self
    {
        $this->surchargeAmount = $surchargeAmount;
        return $this;
    }
}