<?php

namespace Angle\ECF\Node\ECF10\DiscountsOrSurcharges\DiscountOrSurcharge;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

class DiscountOrSurchargeBillingIndicator extends ECFNode
{
    const NODE_NAME = "IndicadorFacturacionDescuentooRecargo";
    const ITBIS1 = 1;
    const ITBIS2 = 2;
    const ITBIS3 = 3;
    const EXEMPT = 4;

    protected static $baseAttributes = [];
    protected static $attributes = [];
    protected static $children = [];
    protected $value;

    public function setChildrenFromDOMNodes(array $children): void
    {
        foreach ($children as $node) {
            if ($node instanceof DOMText) {
                $this->value = $node->nodeValue;
            }
        }
    }

    public function toDOMElement(DOMDocument $dom): DOMElement
    {
        $node = $dom->createElement(self::NODE_NAME, $this->value);
        return $node;
    }

    public function validate(): bool
    {
        return true;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }
}