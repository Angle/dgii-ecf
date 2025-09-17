<?php

namespace Angle\ECF\Node\ECF10\ItemDetails\Item;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;

use Angle\ECF\Node\ECF10\ItemDetails\Item\OtherCurrencyDetails\OtherCurrencyPrice;
use Angle\ECF\Node\ECF10\ItemDetails\Item\OtherCurrencyDetails\OtherCurrencyDiscount;
use Angle\ECF\Node\ECF10\ItemDetails\Item\OtherCurrencyDetails\OtherCurrencySurcharge;
use Angle\ECF\Node\ECF10\ItemDetails\Item\OtherCurrencyDetails\OtherCurrencyItemAmount;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static OtherCurrencyDetails createFromDOMNode(DOMNode $node)
 */
class OtherCurrencyDetails extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "OtraMonedaDetalle";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'otherCurrencyPrice' => [
            'keywords'  => ['PrecioOtraMoneda', 'otherCurrencyPrice'],
            'class'     => OtherCurrencyPrice::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencyDiscount' => [
            'keywords'  => ['DescuentoOtraMoneda', 'otherCurrencyDiscount'],
            'class'     => OtherCurrencyDiscount::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencySurcharge' => [
            'keywords'  => ['RecargoOtraMoneda', 'otherCurrencySurcharge'],
            'class'     => OtherCurrencySurcharge::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencyItemAmount' => [
            'keywords'  => ['MontoItemOtraMoneda', 'otherCurrencyItemAmount'],
            'class'     => OtherCurrencyItemAmount::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var OtherCurrencyPrice|null */
    protected $otherCurrencyPrice;

    /** @var OtherCurrencyDiscount|null */
    protected $otherCurrencyDiscount;

    /** @var OtherCurrencySurcharge|null */
    protected $otherCurrencySurcharge;

    /** @var OtherCurrencyItemAmount|null */
    protected $otherCurrencyItemAmount;


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
                case OtherCurrencyPrice::NODE_NAME:
                    $this->setOtherCurrencyPrice(OtherCurrencyPrice::createFromDOMNode($node));
                    break;
                case OtherCurrencyDiscount::NODE_NAME:
                    $this->setOtherCurrencyDiscount(OtherCurrencyDiscount::createFromDOMNode($node));
                    break;
                case OtherCurrencySurcharge::NODE_NAME:
                    $this->setOtherCurrencySurcharge(OtherCurrencySurcharge::createFromDOMNode($node));
                    break;
                case OtherCurrencyItemAmount::NODE_NAME:
                    $this->setOtherCurrencyItemAmount(OtherCurrencyItemAmount::createFromDOMNode($node));
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

        if ($this->otherCurrencyPrice) {
            $node->appendChild($this->otherCurrencyPrice->toDOMElement($dom));
        }

        if ($this->otherCurrencyDiscount) {
            $node->appendChild($this->otherCurrencyDiscount->toDOMElement($dom));
        }

        if ($this->otherCurrencySurcharge) {
            $node->appendChild($this->otherCurrencySurcharge->toDOMElement($dom));
        }

        if ($this->otherCurrencyItemAmount) {
            $node->appendChild($this->otherCurrencyItemAmount->toDOMElement($dom));
        }

        return $node;
    }


    #########################
    ## VALIDATION
    #########################

    public function validate(): bool
    {
        // TODO: implement the full set of validation
        return true;
    }


    #########################
    ## GETTERS AND SETTERS ##
    #########################

    public function getOtherCurrencyPrice(): ?OtherCurrencyPrice
    {
        return $this->otherCurrencyPrice;
    }

    public function setOtherCurrencyPrice(?OtherCurrencyPrice $price): self
    {
        $this->otherCurrencyPrice = $price;
        return $this;
    }

    public function getOtherCurrencyDiscount(): ?OtherCurrencyDiscount
    {
        return $this->otherCurrencyDiscount;
    }

    public function setOtherCurrencyDiscount(?OtherCurrencyDiscount $discount): self
    {
        $this->otherCurrencyDiscount = $discount;
        return $this;
    }

    public function getOtherCurrencySurcharge(): ?OtherCurrencySurcharge
    {
        return $this->otherCurrencySurcharge;
    }

    public function setOtherCurrencySurcharge(?OtherCurrencySurcharge $surcharge): self
    {
        $this->otherCurrencySurcharge = $surcharge;
        return $this;
    }

    public function getOtherCurrencyItemAmount(): ?OtherCurrencyItemAmount
    {
        return $this->otherCurrencyItemAmount;
    }

    public function setOtherCurrencyItemAmount(?OtherCurrencyItemAmount $amount): self
    {
        $this->otherCurrencyItemAmount = $amount;
        return $this;
    }
}