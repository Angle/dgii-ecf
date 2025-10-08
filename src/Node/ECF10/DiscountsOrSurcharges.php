<?php

namespace Angle\ECF\Node\ECF10;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\Node\ECF10\DiscountsOrSurcharges\DiscountOrSurcharge;

use DateTime;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static DiscountsOrSurcharges createFromDOMNode(DOMNode $node)
 */
class DiscountsOrSurcharges extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "DescuentosORecargos";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'discountOrSurcharge' => [
            'keywords' => ['DescuentoORecargo', 'discountOrSurcharge'],
            'class' => DiscountOrSurcharge::class,
            'type' => ECFNode::CHILD_ARRAY,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var DiscountOrSurcharge[] */
    protected $discountOrSurcharge = [];


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
                case DiscountOrSurcharge::NODE_NAME:
                    $item = DiscountOrSurcharge::createFromDomNode($node);
                    $this->addDiscountOrSurcharge($item);
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

        foreach ($this->discountOrSurcharge as $item) {
            $node->appendChild($item->toDOMElement($dom));
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

    public function getDiscountOrSurcharge(): ?array
    {
        return $this->discountOrSurcharge;
    }

    public function setDiscountOrSurcharge(array $discountOrSurcharge): self
    {
        $this->discountOrSurcharge = $discountOrSurcharge;
        return $this;
    }

    public function addDiscountOrSurcharge(DiscountOrSurcharge $discountOrSurcharge): self
    {
        $this->discountOrSurcharge[] = $discountOrSurcharge;
        return $this;
    }
}