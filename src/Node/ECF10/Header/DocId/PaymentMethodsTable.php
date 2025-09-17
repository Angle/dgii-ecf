<?php

namespace Angle\ECF\Node\ECF10\Header\DocId;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\Node\ECF10\Header\DocId\PaymentMethodsTable\PaymentMethod;
use DateTime;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static PaymentMethodsTable createFromDOMNode(DOMNode $node)
 */
class PaymentMethodsTable extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "TablaFormasPago";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'paymentMethod' => [
            'keywords'  => ['FormaDePago', 'paymentMethod'],
            'class'     => PaymentMethod::class,
            'type'      => ECFNode::CHILD_ARRAY,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var PaymentMethod[] paymentMethods */
    protected $paymentMethods = [];

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
                case PaymentMethod::NODE_NAME:
                    $paymentMethod = PaymentMethod::createFromDomNode($node);
                    $this->addPaymentMethod($paymentMethod);
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

        // Payment Methods node (array)
        foreach ($this->paymentMethods as $paymentMethod) {
            $paymentMethodNode = $paymentMethod->toDOMElement($dom);
            $node->appendChild($paymentMethodNode);
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
     * @return PaymentMethod[]
     */
    public function getPaymentMethods(): ?array
    {
        return $this->paymentMethods;
    }

    /**
     * @param PaymentMethod[] $paymentMethods
     * @return PaymentMethodsTable
     */
    public function setPaymentMethods(array $paymentMethods): self
    {
        $this->paymentMethods = $paymentMethods;
        return $this;
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @return PaymentMethodsTable
     */
    public function addPaymentMethod(PaymentMethod $paymentMethod): self
    {
        $this->paymentMethods[] = $paymentMethod;
        return $this;
    }
}