<?php

namespace Angle\ECF\Node\ECF10\Header;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\CurrencyType;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\ExchangeRate;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyAdditionalTaxesTable;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyExemptAmount;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyTaxableAmountT1;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyTaxableAmountT2;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyTaxableAmountT3;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyTotalItbis;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyTotalItbisT1;
use Angle\ECF\Node\ECF10\Header\OtherCurrency\OtherCurrencyTotalTaxableAmount;
use Angle\ECF\Node\ECF20\Header\OtherCurrency\OtherCurrencyTotalItbisT2;
use Angle\ECF\Node\ECF30\Header\OtherCurrency\OtherCurrencyAdditionalTaxAmount;
use Angle\ECF\Node\ECF30\Header\OtherCurrency\OtherCurrencyTotalAmount;
use Angle\ECF\Node\ECF30\Header\OtherCurrency\OtherCurrencyTotalItbisT3;
use DateTime;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static OtherCurrency createFromDOMNode(DOMNode $node)
 */
class OtherCurrency extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "OtraMoneda";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'currencyType' => [
            'keywords' => ['TipoMoneda', 'currencyType'],
            'class' => CurrencyType::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'exchangeRate' => [
            'keywords' => ['TipoCambio', 'exchangeRate'],
            'class' => ExchangeRate::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencyTotalTaxableAmount' => [
            'keywords' => ['MontoGravadoTotal', 'otherCurrencyTotalTaxableAmount'],
            'class' => OtherCurrencyTotalTaxableAmount::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencyTaxableAmountT1' => [
            'keywords' => ['MontoGravadoI1', 'otherCurrencyTaxableAmountT1'],
            'class' => OtherCurrencyTaxableAmountT1::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencyTaxableAmountT2' => [
            'keywords' => ['MontoGravadoI2', 'otherCurrencyTaxableAmountT2'],
            'class' => OtherCurrencyTaxableAmountT2::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencyTaxableAmountT3' => [
            'keywords' => ['MontoGravadoI3', 'otherCurrencyTaxableAmountT3'],
            'class' => OtherCurrencyTaxableAmountT3::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencyExemptAmount' => [
            'keywords' => ['MontoExento', 'otherCurrencyExemptAmount'],
            'class' => OtherCurrencyExemptAmount::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencyTotalItbis' => [
            'keywords' => ['TotalITBIS', 'otherCurrencyTotalItbis'],
            'class' => OtherCurrencyTotalItbis::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencyTotalItbisT1' => [
            'keywords' => ['TotalITBIS1', 'otherCurrencyTotalItbisT1'],
            'class' => OtherCurrencyTotalItbisT1::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencyTotalItbisT2' => [
            'keywords' => ['TotalITBIS2', 'otherCurrencyTotalItbisT2'],
            'class' => OtherCurrencyTotalItbisT2::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencyTotalItbisT3' => [
            'keywords' => ['TotalITBIS3', 'otherCurrencyTotalItbisT3'],
            'class' => OtherCurrencyTotalItbisT3::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencyAdditionalTaxAmount' => [
            'keywords' => ['MontoImpuestoAdicional', 'otherCurrencyAdditionalTaxAmount'],
            'class' => OtherCurrencyAdditionalTaxAmount::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencyAdditionalTaxesTable' => [
            'keywords' => ['ImpuestosAdicionales', 'otherCurrencyAdditionalTaxesTable'],
            'class' => OtherCurrencyAdditionalTaxesTable::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrencyTotalAmount' => [
            'keywords' => ['MontoTotal', 'otherCurrencyTotalAmount'],
            'class' => OtherCurrencyTotalAmount::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var CurrencyType|null */
    protected $currencyType;

    /** @var ExchangeRate|null */
    protected $exchangeRate;

    /** @var OtherCurrencyTotalTaxableAmount|null */
    protected $otherCurrencyTotalTaxableAmount;

    /** @var OtherCurrencyTaxableAmountT1|null */
    protected $otherCurrencyTaxableAmountT1;

    /** @var OtherCurrencyTaxableAmountT2|null */
    protected $otherCurrencyTaxableAmountT2;

    /** @var OtherCurrencyTaxableAmountT3|null */
    protected $otherCurrencyTaxableAmountT3;

    /** @var OtherCurrencyExemptAmount|null */
    protected $otherCurrencyExemptAmount;

    /** @var OtherCurrencyTotalItbis|null */
    protected $otherCurrencyTotalItbis;

    /** @var OtherCurrencyTotalItbisT1|null */
    protected $otherCurrencyTotalItbisT1;

    /** @var OtherCurrencyTotalItbisT2|null */
    protected $otherCurrencyTotalItbisT2;

    /** @var OtherCurrencyTotalItbisT3|null */
    protected $otherCurrencyTotalItbisT3;

    /** @var OtherCurrencyAdditionalTaxAmount|null */
    protected $otherCurrencyAdditionalTaxAmount;

    /** @var OtherCurrencyAdditionalTaxesTable|null */
    protected $otherCurrencyAdditionalTaxesTable;

    /** @var OtherCurrencyTotalAmount|null */
    protected $otherCurrencyTotalAmount;


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
                case CurrencyType::NODE_NAME: $this->setCurrencyType(CurrencyType::createFromDOMNode($node)); break;
                case ExchangeRate::NODE_NAME: $this->setExchangeRate(ExchangeRate::createFromDOMNode($node)); break;
                case OtherCurrencyTotalTaxableAmount::NODE_NAME: $this->setOtherCurrencyTotalTaxableAmount(OtherCurrencyTotalTaxableAmount::createFromDOMNode($node)); break;
                case OtherCurrencyTaxableAmountT1::NODE_NAME: $this->setOtherCurrencyTaxableAmountT1(OtherCurrencyTaxableAmountT1::createFromDOMNode($node)); break;
                case OtherCurrencyTaxableAmountT2::NODE_NAME: $this->setOtherCurrencyTaxableAmountT2(OtherCurrencyTaxableAmountT2::createFromDOMNode($node)); break;
                case OtherCurrencyTaxableAmountT3::NODE_NAME: $this->setOtherCurrencyTaxableAmountT3(OtherCurrencyTaxableAmountT3::createFromDOMNode($node)); break;
                case OtherCurrencyExemptAmount::NODE_NAME: $this->setOtherCurrencyExemptAmount(OtherCurrencyExemptAmount::createFromDOMNode($node)); break;
                case OtherCurrencyTotalItbis::NODE_NAME: $this->setOtherCurrencyTotalItbis(OtherCurrencyTotalItbis::createFromDOMNode($node)); break;
                case OtherCurrencyTotalItbisT1::NODE_NAME: $this->setOtherCurrencyTotalItbisT1(OtherCurrencyTotalItbisT1::createFromDOMNode($node)); break;
                case OtherCurrencyTotalItbisT2::NODE_NAME: $this->setOtherCurrencyTotalItbisT2(OtherCurrencyTotalItbisT2::createFromDOMNode($node)); break;
                case OtherCurrencyTotalItbisT3::NODE_NAME: $this->setOtherCurrencyTotalItbisT3(OtherCurrencyTotalItbisT3::createFromDOMNode($node)); break;
                case OtherCurrencyAdditionalTaxAmount::NODE_NAME: $this->setOtherCurrencyAdditionalTaxAmount(OtherCurrencyAdditionalTaxAmount::createFromDOMNode($node)); break;
                case OtherCurrencyAdditionalTaxesTable::NODE_NAME: $this->setOtherCurrencyAdditionalTaxesTable(OtherCurrencyAdditionalTaxesTable::createFromDOMNode($node)); break;
                case OtherCurrencyTotalAmount::NODE_NAME: $this->setOtherCurrencyTotalAmount(OtherCurrencyTotalAmount::createFromDOMNode($node)); break;
            }
        }
    }


    #########################
    ## ECF NODE TO DOM TRANSLATION
    #########################

    public function toDOMElement(DOMDocument $dom): DOMElement
    {
        $node = $dom->createElement(self::NODE_NAME);

        if ($this->currencyType) $node->appendChild($this->currencyType->toDOMElement($dom));
        if ($this->exchangeRate) $node->appendChild($this->exchangeRate->toDOMElement($dom));
        if ($this->otherCurrencyTotalTaxableAmount) $node->appendChild($this->otherCurrencyTotalTaxableAmount->toDOMElement($dom));
        if ($this->otherCurrencyTaxableAmountT1) $node->appendChild($this->otherCurrencyTaxableAmountT1->toDOMElement($dom));
        if ($this->otherCurrencyTaxableAmountT2) $node->appendChild($this->otherCurrencyTaxableAmountT2->toDOMElement($dom));
        if ($this->otherCurrencyTaxableAmountT3) $node->appendChild($this->otherCurrencyTaxableAmountT3->toDOMElement($dom));
        if ($this->otherCurrencyExemptAmount) $node->appendChild($this->otherCurrencyExemptAmount->toDOMElement($dom));
        if ($this->otherCurrencyTotalItbis) $node->appendChild($this->otherCurrencyTotalItbis->toDOMElement($dom));
        if ($this->otherCurrencyTotalItbisT1) $node->appendChild($this->otherCurrencyTotalItbisT1->toDOMElement($dom));
        if ($this->otherCurrencyTotalItbisT2) $node->appendChild($this->otherCurrencyTotalItbisT2->toDOMElement($dom));
        if ($this->otherCurrencyTotalItbisT3) $node->appendChild($this->otherCurrencyTotalItbisT3->toDOMElement($dom));
        if ($this->otherCurrencyAdditionalTaxAmount) $node->appendChild($this->otherCurrencyAdditionalTaxAmount->toDOMElement($dom));
        if ($this->otherCurrencyAdditionalTaxesTable) $node->appendChild($this->otherCurrencyAdditionalTaxesTable->toDOMElement($dom));
        if ($this->otherCurrencyTotalAmount) $node->appendChild($this->otherCurrencyTotalAmount->toDOMElement($dom));

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

    public function getCurrencyType(): ?CurrencyType { return $this->currencyType; }
    public function setCurrencyType(?CurrencyType $currencyType): self { $this->currencyType = $currencyType; return $this; }

    public function getExchangeRate(): ?ExchangeRate { return $this->exchangeRate; }
    public function setExchangeRate(?ExchangeRate $exchangeRate): self { $this->exchangeRate = $exchangeRate; return $this; }

    public function getOtherCurrencyTotalTaxableAmount(): ?OtherCurrencyTotalTaxableAmount { return $this->otherCurrencyTotalTaxableAmount; }
    public function setOtherCurrencyTotalTaxableAmount(?OtherCurrencyTotalTaxableAmount $amount): self { $this->otherCurrencyTotalTaxableAmount = $amount; return $this; }

    public function getOtherCurrencyTaxableAmountT1(): ?OtherCurrencyTaxableAmountT1 { return $this->otherCurrencyTaxableAmountT1; }
    public function setOtherCurrencyTaxableAmountT1(?OtherCurrencyTaxableAmountT1 $amount): self { $this->otherCurrencyTaxableAmountT1 = $amount; return $this; }

    public function getOtherCurrencyTaxableAmountT2(): ?OtherCurrencyTaxableAmountT2 { return $this->otherCurrencyTaxableAmountT2; }
    public function setOtherCurrencyTaxableAmountT2(?OtherCurrencyTaxableAmountT2 $amount): self { $this->otherCurrencyTaxableAmountT2 = $amount; return $this; }

    public function getOtherCurrencyTaxableAmountT3(): ?OtherCurrencyTaxableAmountT3 { return $this->otherCurrencyTaxableAmountT3; }
    public function setOtherCurrencyTaxableAmountT3(?OtherCurrencyTaxableAmountT3 $amount): self { $this->otherCurrencyTaxableAmountT3 = $amount; return $this; }

    public function getOtherCurrencyExemptAmount(): ?OtherCurrencyExemptAmount { return $this->otherCurrencyExemptAmount; }
    public function setOtherCurrencyExemptAmount(?OtherCurrencyExemptAmount $amount): self { $this->otherCurrencyExemptAmount = $amount; return $this; }

    public function getOtherCurrencyTotalItbis(): ?OtherCurrencyTotalItbis { return $this->otherCurrencyTotalItbis; }
    public function setOtherCurrencyTotalItbis(?OtherCurrencyTotalItbis $totalItbis): self { $this->otherCurrencyTotalItbis = $totalItbis; return $this; }

    public function getOtherCurrencyTotalItbisT1(): ?OtherCurrencyTotalItbisT1 { return $this->otherCurrencyTotalItbisT1; }
    public function setOtherCurrencyTotalItbisT1(?OtherCurrencyTotalItbisT1 $totalItbis): self { $this->otherCurrencyTotalItbisT1 = $totalItbis; return $this; }

    public function getOtherCurrencyTotalItbisT2(): ?OtherCurrencyTotalItbisT2 { return $this->otherCurrencyTotalItbisT2; }
    public function setOtherCurrencyTotalItbisT2(?OtherCurrencyTotalItbisT2 $totalItbis): self { $this->otherCurrencyTotalItbisT2 = $totalItbis; return $this; }

    public function getOtherCurrencyTotalItbisT3(): ?OtherCurrencyTotalItbisT3 { return $this->otherCurrencyTotalItbisT3; }
    public function setOtherCurrencyTotalItbisT3(?OtherCurrencyTotalItbisT3 $totalItbis): self { $this->otherCurrencyTotalItbisT3 = $totalItbis; return $this; }

    public function getOtherCurrencyAdditionalTaxAmount(): ?OtherCurrencyAdditionalTaxAmount { return $this->otherCurrencyAdditionalTaxAmount; }
    public function setOtherCurrencyAdditionalTaxAmount(?OtherCurrencyAdditionalTaxAmount $amount): self { $this->otherCurrencyAdditionalTaxAmount = $amount; return $this; }

    public function getOtherCurrencyAdditionalTaxesTable(): ?OtherCurrencyAdditionalTaxesTable { return $this->otherCurrencyAdditionalTaxesTable; }
    public function setOtherCurrencyAdditionalTaxesTable(?OtherCurrencyAdditionalTaxesTable $table): self { $this->otherCurrencyAdditionalTaxesTable = $table; return $this; }

    public function getOtherCurrencyTotalAmount(): ?OtherCurrencyTotalAmount { return $this->otherCurrencyTotalAmount; }
    public function setOtherCurrencyTotalAmount(?OtherCurrencyTotalAmount $amount): self { $this->otherCurrencyTotalAmount = $amount; return $this; }
}