<?php

namespace Angle\ECF\Node\ECF10\Header\DocId\PaymentMethodsTable;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\Node\ECF10\Header\DocId\PaymentMethodsTable\PaymentMethod\PaymentAmount;
use Angle\ECF\Node\ECF10\Header\DocId\PaymentMethodsTable\PaymentMethod\PaymentMethodType;
use DateTime;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static PaymentMethod createFromDOMNode(DOMNode $node)
 */
class PaymentMethod extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "FormaDePago";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'paymentMethodType' => [
            'keywords'  => ['FormaPago', 'paymentMethodType'],
            'class'     => PaymentMethodType::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'paymentAmount' => [
            'keywords'  => ['MontoPago', 'paymentAmount'],
            'class'     => PaymentAmount::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
    ];

    private $value;


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var PaymentMethodType */
    protected $paymentMethodType;

    /** @var PaymentAmount */
    protected $paymentAmount;


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
                continue;
            }

            switch ($node->localName) {
                case PaymentMethodType::NODE_NAME:
                    $this->setPaymentMethodType(PaymentMethodType::createFromDOMNode($node));
                    break;
                case PaymentAmount::NODE_NAME:
                    $this->setPaymentAmount(PaymentAmount::createFromDOMNode($node));
                    break;
            }
        }
    }


    #########################
    ## CFDI NODE TO DOM TRANSLATION
    #########################

    public function toDOMElement(DOMDocument $dom): DOMElement
    {
        $node = $dom->createElement(self::NODE_NAME);

        // EcfType
        if ($this->paymentMethodType) {
            $node->appendChild($this->paymentMethodType->toDOMElement($dom));
        }

        // Encf
        if ($this->paymentAmount) {
            $node->appendChild($this->paymentAmount->toDOMElement($dom));
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



    #########################
    ##       CHILDREN      ##
    #########################

    public function getPaymentMethodType(): ?PaymentMethodType
    {
        return $this->paymentMethodType;
    }

    public function setPaymentMethodType(PaymentMethodType $paymentMethodType): self
    {
        $this->paymentMethodType = $paymentMethodType;
        return $this;
    }

    public function getPaymentAmount(): ?PaymentAmount
    {
        return $this->paymentAmount;
    }

    public function setPaymentAmount(PaymentAmount $paymentAmount): self
    {
        $this->paymentAmount = $paymentAmount;
        return $this;
    }
}