<?php

namespace Angle\ECF\Node\ECF10\ItemDetails\Item\OtherCurrencyDetails;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

class OtherCurrencyItemAmount extends ECFNode
{
    const NODE_NAME = "MontoItemOtraMoneda";
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