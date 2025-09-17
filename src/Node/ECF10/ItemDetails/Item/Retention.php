<?php

namespace Angle\ECF\Node\ECF10\ItemDetails\Item;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;

use Angle\ECF\Node\ECF10\ItemDetails\Item\Retention\RetentionOrPerceptionAgentIndicator;
use Angle\ECF\Node\ECF10\ItemDetails\Item\Retention\ItbisWithheldAmount;
use Angle\ECF\Node\ECF10\ItemDetails\Item\Retention\IsrWithheldAmount;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static Retention createFromDOMNode(DOMNode $node)
 */
class Retention extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "Retencion";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'retentionOrPerceptionAgentIndicator' => [
            'keywords'  => ['IndicadorAgenteRetencionoPercepcion', 'retentionOrPerceptionAgentIndicator'],
            'class'     => RetentionOrPerceptionAgentIndicator::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'itbisWithheldAmount' => [
            'keywords'  => ['MontoITBISRetenido', 'itbisWithheldAmount'],
            'class'     => ItbisWithheldAmount::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'isrWithheldAmount' => [
            'keywords'  => ['MontoISRRetenido', 'isrWithheldAmount'],
            'class'     => IsrWithheldAmount::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var RetentionOrPerceptionAgentIndicator|null */
    protected $retentionOrPerceptionAgentIndicator;

    /** @var ItbisWithheldAmount|null */
    protected $itbisWithheldAmount;

    /** @var IsrWithheldAmount|null */
    protected $isrWithheldAmount;


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
                case RetentionOrPerceptionAgentIndicator::NODE_NAME:
                    $this->setRetentionOrPerceptionAgentIndicator(RetentionOrPerceptionAgentIndicator::createFromDOMNode($node));
                    break;
                case ItbisWithheldAmount::NODE_NAME:
                    $this->setItbisWithheldAmount(ItbisWithheldAmount::createFromDOMNode($node));
                    break;
                case IsrWithheldAmount::NODE_NAME:
                    $this->setIsrWithheldAmount(IsrWithheldAmount::createFromDOMNode($node));
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

        if ($this->retentionOrPerceptionAgentIndicator) {
            $node->appendChild($this->retentionOrPerceptionAgentIndicator->toDOMElement($dom));
        }

        if ($this->itbisWithheldAmount) {
            $node->appendChild($this->itbisWithheldAmount->toDOMElement($dom));
        }

        if ($this->isrWithheldAmount) {
            $node->appendChild($this->isrWithheldAmount->toDOMElement($dom));
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

    public function getRetentionOrPerceptionAgentIndicator(): ?RetentionOrPerceptionAgentIndicator
    {
        return $this->retentionOrPerceptionAgentIndicator;
    }

    public function setRetentionOrPerceptionAgentIndicator(?RetentionOrPerceptionAgentIndicator $indicator): self
    {
        $this->retentionOrPerceptionAgentIndicator = $indicator;
        return $this;
    }

    public function getItbisWithheldAmount(): ?ItbisWithheldAmount
    {
        return $this->itbisWithheldAmount;
    }

    public function setItbisWithheldAmount(?ItbisWithheldAmount $amount): self
    {
        $this->itbisWithheldAmount = $amount;
        return $this;
    }

    public function getIsrWithheldAmount(): ?IsrWithheldAmount
    {
        return $this->isrWithheldAmount;
    }

    public function setIsrWithheldAmount(?IsrWithheldAmount $amount): self
    {
        $this->isrWithheldAmount = $amount;
        return $this;
    }
}