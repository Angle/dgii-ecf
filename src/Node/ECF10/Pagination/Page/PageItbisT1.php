<?php

namespace Angle\ECF\Node\ECF10\Pagination\Page;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;

use DateTime;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static PageItbisT1 createFromDOMNode(DOMNode $node)
 */
class PageItbisT1 extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "SubtotalItbis1Pagina";

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
    ##   SPECIAL METHODS   ##
    #########################

    public static function newWithValue($value): PageItbisT1
    {
        $pageItbisT1 = new PageItbisT1([]);
        $pageItbisT1->setValue($value);

        return $pageItbisT1;
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