<?php

namespace Angle\ECF\Node\ECF10\Header\Totals;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;

use DateTime;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static TotalItbisT1 createFromDOMNode(DOMNode $node)
 */
class TotalItbisT1 extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "TotalITBIS1";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        //TODO: Implement children
    ];

    private $value;


    #########################
    ##      PROPERTIES     ##
    #########################


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
                $this->value = $node->nodeValue;
            }
        }
    }


    #########################
    ## CFDI NODE TO DOM TRANSLATION
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
}