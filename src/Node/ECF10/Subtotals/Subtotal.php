<?php

namespace Angle\ECF\Node\ECF10\Subtotals;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;

use Angle\ECF\Node\ECF10\Subtotals\Subtotal\SubtotalNumber;
use Angle\ECF\Node\ECF10\Subtotals\Subtotal\SubtotalDescription;
use Angle\ECF\Node\ECF10\Subtotals\Subtotal\Order;
use Angle\ECF\Node\ECF10\Subtotals\Subtotal\SubtotalTotalTaxableAmount;
use Angle\ECF\Node\ECF10\Subtotals\Subtotal\SubtotalTaxableAmountT1;
use Angle\ECF\Node\ECF10\Subtotals\Subtotal\SubtotalTaxableAmountT2;
use Angle\ECF\Node\ECF10\Subtotals\Subtotal\SubtotalTaxableAmountT3;
use Angle\ECF\Node\ECF10\Subtotals\Subtotal\SubtotalItbis;
use Angle\ECF\Node\ECF10\Subtotals\Subtotal\SubtotalItbisT1;
use Angle\ECF\Node\ECF10\Subtotals\Subtotal\SubtotalItbisT2;
use Angle\ECF\Node\ECF10\Subtotals\Subtotal\SubtotalItbisT3;
use Angle\ECF\Node\ECF10\Subtotals\Subtotal\SubtotalAdditionalTaxAmount;
use Angle\ECF\Node\ECF10\Subtotals\Subtotal\SubtotalExemptAmount;
use Angle\ECF\Node\ECF10\Subtotals\Subtotal\SubtotalAmount;
use Angle\ECF\Node\ECF10\Subtotals\Subtotal\Lines;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static Subtotal createFromDOMNode(DOMNode $node)
 */
class Subtotal extends ECFNode
{
    const NODE_NAME = "Subtotal";
    protected static $children = [
        'subtotalNumber' => [
            'keywords' => ['NumeroSubTotal', 'subtotalNumber'],
            'class' => SubtotalNumber::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'subtotalDescription' => [
            'keywords' => ['DescripcionSubtotal', 'subtotalDescription'],
            'class' => SubtotalDescription::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'order' => [
            'keywords' => ['Orden', 'order'],
            'class' => Order::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'subtotalTotalTaxableAmount' => [
            'keywords' => ['SubTotalMontoGravadoTotal', 'subtotalTotalTaxableAmount'],
            'class' => SubtotalTotalTaxableAmount::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'subtotalTaxableAmountT1' => [
            'keywords' => ['SubTotalMontoGravadoI1', 'subtotalTaxableAmountT1'],
            'class' => SubtotalTaxableAmountT1::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'subtotalTaxableAmountT2' => [
            'keywords' => ['SubTotalMontoGravadoI2', 'subtotalTaxableAmountT2'],
            'class' => SubtotalTaxableAmountT2::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'subtotalTaxableAmountT3' => [
            'keywords' => ['SubTotalMontoGravadoI3', 'subtotalTaxableAmountT3'],
            'class' => SubtotalTaxableAmountT3::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'subtotalItbis' => [
            'keywords' => ['SubTotaITBIS', 'subtotalItbis'],
            'class' => SubtotalItbis::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'subtotalItbisT1' => [
            'keywords' => ['SubTotaITBIS1', 'subtotalItbisT1'],
            'class' => SubtotalItbisT1::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'subtotalItbisT2' => [
            'keywords' => ['SubTotaITBIS2', 'subtotalItbisT2'],
            'class' => SubtotalItbisT2::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'subtotalItbisT3' => [
            'keywords' => ['SubTotaITBIS3', 'subtotalItbisT3'],
            'class' => SubtotalItbisT3::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'subtotalAdditionalTaxAmount' => [
            'keywords' => ['SubTotalImpuestoAdicional', 'subtotalAdditionalTaxAmount'],
            'class' => SubtotalAdditionalTaxAmount::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'subtotalExemptAmount' => [
            'keywords' => ['SubTotalExento', 'subtotalExemptAmount'],
            'class' => SubtotalExemptAmount::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'subtotalAmount' => [
            'keywords' => ['MontoSubTotal', 'subtotalAmount'],
            'class' => SubtotalAmount::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
        'lines' => [
            'keywords' => ['Lineas', 'lines'],
            'class' => Lines::class,
            'type' => ECFNode::CHILD_UNIQUE
        ],
    ];

    protected $subtotalNumber;
    protected $subtotalDescription;
    protected $order;
    protected $subtotalTotalTaxableAmount;
    protected $subtotalTaxableAmountT1;
    protected $subtotalTaxableAmountT2;
    protected $subtotalTaxableAmountT3;
    protected $subtotalItbis;
    protected $subtotalItbisT1;
    protected $subtotalItbisT2;
    protected $subtotalItbisT3;
    protected $subtotalAdditionalTaxAmount;
    protected $subtotalExemptAmount;
    protected $subtotalAmount;
    protected $lines;

    public function setChildrenFromDOMNodes(array $children): void
    {
        foreach ($children as $node) {
            if ($node instanceof DOMText) continue;
            switch ($node->localName) {
                case SubtotalNumber::NODE_NAME: $this->setSubtotalNumber(SubtotalNumber::createFromDOMNode($node)); break;
                case SubtotalDescription::NODE_NAME: $this->setSubtotalDescription(SubtotalDescription::createFromDOMNode($node)); break;
                case Order::NODE_NAME: $this->setOrder(Order::createFromDOMNode($node)); break;
                case SubtotalTotalTaxableAmount::NODE_NAME: $this->setSubtotalTotalTaxableAmount(SubtotalTotalTaxableAmount::createFromDOMNode($node)); break;
                case SubtotalTaxableAmountT1::NODE_NAME: $this->setSubtotalTaxableAmountT1(SubtotalTaxableAmountT1::createFromDOMNode($node)); break;
                case SubtotalTaxableAmountT2::NODE_NAME: $this->setSubtotalTaxableAmountT2(SubtotalTaxableAmountT2::createFromDOMNode($node)); break;
                case SubtotalTaxableAmountT3::NODE_NAME: $this->setSubtotalTaxableAmountT3(SubtotalTaxableAmountT3::createFromDOMNode($node)); break;
                case SubtotalItbis::NODE_NAME: $this->setSubtotalItbis(SubtotalItbis::createFromDOMNode($node)); break;
                case SubtotalItbisT1::NODE_NAME: $this->setSubtotalItbisT1(SubtotalItbisT1::createFromDOMNode($node)); break;
                case SubtotalItbisT2::NODE_NAME: $this->setSubtotalItbisT2(SubtotalItbisT2::createFromDOMNode($node)); break;
                case SubtotalItbisT3::NODE_NAME: $this->setSubtotalItbisT3(SubtotalItbisT3::createFromDOMNode($node)); break;
                case SubtotalAdditionalTaxAmount::NODE_NAME: $this->setSubtotalAdditionalTaxAmount(SubtotalAdditionalTaxAmount::createFromDOMNode($node)); break;
                case SubtotalExemptAmount::NODE_NAME: $this->setSubtotalExemptAmount(SubtotalExemptAmount::createFromDOMNode($node)); break;
                case SubtotalAmount::NODE_NAME: $this->setSubtotalAmount(SubtotalAmount::createFromDOMNode($node)); break;
                case Lines::NODE_NAME: $this->setLines(Lines::createFromDOMNode($node)); break;
            }
        }
    }

    public function toDOMElement(DOMDocument $dom): DOMElement
    {
        $node = $dom->createElement(self::NODE_NAME);
        if ($this->subtotalNumber) $node->appendChild($this->subtotalNumber->toDOMElement($dom));
        if ($this->subtotalDescription) $node->appendChild($this->subtotalDescription->toDOMElement($dom));
        if ($this->order) $node->appendChild($this->order->toDOMElement($dom));
        if ($this->subtotalTotalTaxableAmount) $node->appendChild($this->subtotalTotalTaxableAmount->toDOMElement($dom));
        if ($this->subtotalTaxableAmountT1) $node->appendChild($this->subtotalTaxableAmountT1->toDOMElement($dom));
        if ($this->subtotalTaxableAmountT2) $node->appendChild($this->subtotalTaxableAmountT2->toDOMElement($dom));
        if ($this->subtotalTaxableAmountT3) $node->appendChild($this->subtotalTaxableAmountT3->toDOMElement($dom));
        if ($this->subtotalItbis) $node->appendChild($this->subtotalItbis->toDOMElement($dom));
        if ($this->subtotalItbisT1) $node->appendChild($this->subtotalItbisT1->toDOMElement($dom));
        if ($this->subtotalItbisT2) $node->appendChild($this->subtotalItbisT2->toDOMElement($dom));
        if ($this->subtotalItbisT3) $node->appendChild($this->subtotalItbisT3->toDOMElement($dom));
        if ($this->subtotalAdditionalTaxAmount) $node->appendChild($this->subtotalAdditionalTaxAmount->toDOMElement($dom));
        if ($this->subtotalExemptAmount) $node->appendChild($this->subtotalExemptAmount->toDOMElement($dom));
        if ($this->subtotalAmount) $node->appendChild($this->subtotalAmount->toDOMElement($dom));
        if ($this->lines) $node->appendChild($this->lines->toDOMElement($dom));
        return $node;
    }

    public function validate(): bool {
        return true;
    }


    public function getSubtotalNumber(): ?SubtotalNumber
    {
        return $this->subtotalNumber;
    }
    public function setSubtotalNumber(?SubtotalNumber $subtotalNumber): self
    {
        $this->subtotalNumber = $subtotalNumber;
        return $this;
    }
    public function getSubtotalDescription(): ?SubtotalDescription
    {
        return $this->subtotalDescription;
    }
    public function setSubtotalDescription(?SubtotalDescription $subtotalDescription): self
    {
        $this->subtotalDescription = $subtotalDescription;
        return $this;
    }
    public function getOrder(): ?Order
    {
        return $this->order;
    }
    public function setOrder(?Order $order): self
    {
        $this->order = $order;
        return $this;
    }
    public function getSubtotalTotalTaxableAmount(): ?SubtotalTotalTaxableAmount
    {
        return $this->subtotalTotalTaxableAmount;
    }
    public function setSubtotalTotalTaxableAmount(?SubtotalTotalTaxableAmount $subtotalTotalTaxableAmount): self
    {
        $this->subtotalTotalTaxableAmount = $subtotalTotalTaxableAmount;
        return $this;
    }
    public function getSubtotalTaxableAmountT1(): ?SubtotalTaxableAmountT1
    {
        return $this->subtotalTaxableAmountT1;
    }
    public function setSubtotalTaxableAmountT1(?SubtotalTaxableAmountT1 $subtotalTaxableAmountT1): self
    {
        $this->subtotalTaxableAmountT1 = $subtotalTaxableAmountT1;
        return $this;
    }
    public function getSubtotalTaxableAmountT2(): ?SubtotalTaxableAmountT2
    {
        return $this->subtotalTaxableAmountT2;
    }
    public function setSubtotalTaxableAmountT2(?SubtotalTaxableAmountT2 $subtotalTaxableAmountT2): self
    {
        $this->subtotalTaxableAmountT2 = $subtotalTaxableAmountT2;
        return $this;
    }
    public function getSubtotalTaxableAmountT3(): ?SubtotalTaxableAmountT3
    {
        return $this->subtotalTaxableAmountT3;
    }
    public function setSubtotalTaxableAmountT3(?SubtotalTaxableAmountT3 $subtotalTaxableAmountT3): self
    {
        $this->subtotalTaxableAmountT3 = $subtotalTaxableAmountT3;
        return $this;
    }
    public function getSubtotalItbis(): ?SubtotalItbis
    {
        return $this->subtotalItbis;
    }
    public function setSubtotalItbis(?SubtotalItbis $subtotalItbis): self
    {
        $this->subtotalItbis = $subtotalItbis;
        return $this;
    }
    public function getSubtotalItbisT1(): ?SubtotalItbisT1
    {
        return $this->subtotalItbisT1;
    }
    public function setSubtotalItbisT1(?SubtotalItbisT1 $subtotalItbisT1): self
    {
        $this->subtotalItbisT1 = $subtotalItbisT1;
        return $this;
    }
    public function getSubtotalItbisT2(): ?SubtotalItbisT2
    {
        return $this->subtotalItbisT2;
    }
    public function setSubtotalItbisT2(?SubtotalItbisT2 $subtotalItbisT2): self
    {
        $this->subtotalItbisT2 = $subtotalItbisT2;
        return $this;
    }
    public function getSubtotalItbisT3(): ?SubtotalItbisT3
    {
        return $this->subtotalItbisT3;
    }
    public function setSubtotalItbisT3(?SubtotalItbisT3 $subtotalItbisT3): self
    {
        $this->subtotalItbisT3 = $subtotalItbisT3;
        return $this;
    }
    public function getSubtotalAdditionalTaxAmount(): ?SubtotalAdditionalTaxAmount
    {
        return $this->subtotalAdditionalTaxAmount;
    }
    public function setSubtotalAdditionalTaxAmount(?SubtotalAdditionalTaxAmount $subtotalAdditionalTaxAmount): self
    {
        $this->subtotalAdditionalTaxAmount = $subtotalAdditionalTaxAmount;
        return $this;
    }
    public function getSubtotalExemptAmount(): ?SubtotalExemptAmount
    {
        return $this->subtotalExemptAmount;
    }
    public function setSubtotalExemptAmount(?SubtotalExemptAmount $subtotalExemptAmount): self
    {
        $this->subtotalExemptAmount = $subtotalExemptAmount;
        return $this;
    }
    public function getSubtotalAmount(): ?SubtotalAmount
    {
        return $this->subtotalAmount;
    }
    public function setSubtotalAmount(?SubtotalAmount $subtotalAmount): self
    {
        $this->subtotalAmount = $subtotalAmount;
        return $this;
    }
    public function getLines(): ?Lines
    {
        return $this->lines;
    }
    public function setLines(?Lines $lines): self
    {
        $this->lines = $lines;
        return $this;
    }
}