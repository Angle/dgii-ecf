<?php

namespace Angle\ECF\Node\ECF10\Header\Totals;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable\AdditionalTax;
use DateTime;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static AdditionalTaxesTable createFromDOMNode(DOMNode $node)
 */
class AdditionalTaxesTable extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "ImpuestosAdicionales";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'additionalTax' => [
            'keywords'  => ['ImpuestoAdicional', 'additionalTax'],
            'class'     => AdditionalTax::class,
            'type'      => ECFNode::CHILD_ARRAY,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var AdditionalTax[] additionalTax */
    protected $additionalTaxes = [];

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
                case AdditionalTax::NODE_NAME:
                    $additionalTaxes = AdditionalTax::createFromDomNode($node);
                    $this->addAdditionalTax($additionalTaxes);
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

        // Additional Tax node (array)
        foreach ($this->additionalTaxes as $additionalTax) {
            $additionalTaxNode = $additionalTax->toDOMElement($dom);
            $node->appendChild($additionalTaxNode);
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
     * @return AdditionalTax[]
     */
    public function getAdditionalTaxes(): ?array
    {
        return $this->additionalTaxes;
    }

    /**
     * @param AdditionalTax[] $additionalTaxes
     * @return AdditionalTaxesTable
     */
    public function setAdditionalTaxes(array $additionalTaxes): self
    {
        $this->additionalTaxes = $additionalTaxes;
        return $this;
    }

    /**
     * @param AdditionalTax $additionalTax
     * @return AdditionalTaxesTable
     */
    public function addAdditionalTax(AdditionalTax $additionalTax): self
    {
        $this->additionalTaxes[] = $additionalTax;
        return $this;
    }
}