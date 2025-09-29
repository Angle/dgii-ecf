<?php

namespace Angle\ECF\Node\ECF10\Pagination\Page;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;

use Angle\ECF\Node\ECF10\Pagination\Page\SubtotalAdditionalTax\PageSpecificConsumptionTaxAmount;
use Angle\ECF\Node\ECF10\Pagination\Page\SubtotalAdditionalTax\PageOtherTaxesSubtotal;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static SubtotalAdditionalTax createFromDOMNode(DOMNode $node)
 */
class SubtotalAdditionalTax extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "SubtotalImpuestoAdicional";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'pageSpecificConsumptionTaxAmount' => [
            'keywords' => ['SubtotalImpuestoSelectivoConsumoEspecificoPagina', 'pageSpecificConsumptionTaxAmount'],
            'class' => PageSpecificConsumptionTaxAmount::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'pageOtherTaxesSubtotal' => [
            'keywords' => ['SubtotalOtrosImpuesto', 'pageOtherTaxesSubtotal'],
            'class' => PageOtherTaxesSubtotal::class,
            'type' => ECFNode::CHILD_UNIQUE],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var PageSpecificConsumptionTaxAmount $pageSpecificConsumptionTaxAmount */
    protected $pageSpecificConsumptionTaxAmount;

    /** @var PageOtherTaxesSubtotal $pageOtherTaxesSubtotal */
    protected $pageOtherTaxesSubtotal;


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
                case PageSpecificConsumptionTaxAmount::NODE_NAME:
                    $this->setPageSpecificConsumptionTaxAmount(PageSpecificConsumptionTaxAmount::createFromDOMNode($node));
                    break;
                case PageOtherTaxesSubtotal::NODE_NAME:
                    $this->setPageOtherTaxesSubtotal(PageOtherTaxesSubtotal::createFromDOMNode($node));
                    break;
            }
        }
    }


    #########################
    ##   SPECIAL METHODS   ##
    #########################

    public function toDOMElement(DOMDocument $dom): DOMElement
    {
        $node = $dom->createElement(self::NODE_NAME);
        if ($this->pageSpecificConsumptionTaxAmount) $node->appendChild($this->pageSpecificConsumptionTaxAmount->toDOMElement($dom));
        if ($this->pageOtherTaxesSubtotal) $node->appendChild($this->pageOtherTaxesSubtotal->toDOMElement($dom));
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

    public function getPageSpecificConsumptionTaxAmount(): ?PageSpecificConsumptionTaxAmount
    {
        return $this->pageSpecificConsumptionTaxAmount;

    }

    public function setPageSpecificConsumptionTaxAmount(?PageSpecificConsumptionTaxAmount $amount): self
    {
        $this->pageSpecificConsumptionTaxAmount = $amount;
        return $this;
    }

    public function getPageOtherTaxesSubtotal(): ?PageOtherTaxesSubtotal
    {
        return $this->pageOtherTaxesSubtotal;
    }

    public function setPageOtherTaxesSubtotal(?PageOtherTaxesSubtotal $subtotal): self
    {
        $this->pageOtherTaxesSubtotal = $subtotal;
        return $this;
    }
}