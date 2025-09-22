<?php

namespace Angle\ECF\Node\ECF10\ItemDetails\Item;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;

use DateTime;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static BillingIndicator createFromDOMNode(DOMNode $node)
 */
class BillingIndicator extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "IndicadorFacturacion";

    const NOT_BILLED = 0;
    const ITBIS1 = 1;
    const ITBIS2 = 2;
    const ITBIS3 = 3;
    const EXEMPT = 4;

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [];

    protected $value;


    #########################
    ##      PROPERTIES     ##
    #########################


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
                $this->value = $node->nodeValue;
            }
        }
    }


    #########################
    ## ECF NODE TO DOM TRANSLATION
    #########################

    public function toDOMElement(DOMDocument $dom): DOMElement
    {
        $node = $dom->createElement(self::NODE_NAME);

        $node->nodeValue = $this->value;

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

    public function setValue(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}