<?php

namespace Angle\ECF\Node\ECF10\Header;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;

use Angle\ECF\Node\ECF10\Header\Totals\TotalTaxableAmount;
use Angle\ECF\Node\ECF10\Header\Totals\TaxableAmountT1;
use Angle\ECF\Node\ECF10\Header\Totals\TaxableAmountT2;
use Angle\ECF\Node\ECF10\Header\Totals\TaxableAmountT3;
use Angle\ECF\Node\ECF10\Header\Totals\ExemptAmount;
use Angle\ECF\Node\ECF10\Header\Totals\ItbisT1;
use Angle\ECF\Node\ECF10\Header\Totals\ItbisT2;
use Angle\ECF\Node\ECF10\Header\Totals\ItbisT3;
use Angle\ECF\Node\ECF10\Header\Totals\TotalItbis;
use Angle\ECF\Node\ECF10\Header\Totals\TotalItbisT1;
use Angle\ECF\Node\ECF10\Header\Totals\TotalItbisT2;
use Angle\ECF\Node\ECF10\Header\Totals\TotalItbisT3;
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxAmount;
use Angle\ECF\Node\ECF10\Header\Totals\AdditionalTaxesTable;
use Angle\ECF\Node\ECF10\Header\Totals\TotalAmount;
use Angle\ECF\Node\ECF10\Header\Totals\NonBillableAmount;
use Angle\ECF\Node\ECF10\Header\Totals\PeriodAmount;
use Angle\ECF\Node\ECF10\Header\Totals\PreviousBalance;
use Angle\ECF\Node\ECF10\Header\Totals\AdvancedPaymentAmount;
use Angle\ECF\Node\ECF10\Header\Totals\AmountToPay;
use Angle\ECF\Node\ECF10\Header\Totals\TotalItbisWithheld;
use Angle\ECF\Node\ECF10\Header\Totals\TotalIsrWithheld;
use Angle\ECF\Node\ECF10\Header\Totals\TotalItbisPerception;
use Angle\ECF\Node\ECF10\Header\Totals\TotalIsrPerception;

use DateTime;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static Totals createFromDOMNode(DOMNode $node)
 */
class Totals extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "Totales";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'totalTaxableAmount' => [
            'keywords' => ['MontoGravadoTotal', 'totalTaxableAmount'],
            'class' => TotalTaxableAmount::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'taxableAmountT1' => [
            'keywords' => ['MontoGravadoI1', 'taxableAmountT1'],
            'class' => TaxableAmountT1::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'taxableAmountT2' => [
            'keywords' => ['MontoGravadoI2', 'taxableAmountT2'],
            'class' => TaxableAmountT2::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'taxableAmountT3' => [
            'keywords' => ['MontoGravadoI3', 'taxableAmountT3'],
            'class' => TaxableAmountT3::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'exemptAmount' => [
            'keywords' => ['MontoExento', 'exemptAmount'],
            'class' => ExemptAmount::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'itbisT1' => [
            'keywords' => ['ITBIS1', 'itbisT1'],
            'class' => ItbisT1::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'itbisT2' => [
            'keywords' => ['ITBIS2', 'itbisT2'],
            'class' => ItbisT2::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'itbisT3' => [
            'keywords' => ['ITBIS3', 'itbisT3'],
            'class' => ItbisT3::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'totalItbis' => [
            'keywords' => ['TotalITBIS', 'totalItbis'],
            'class' => TotalItbis::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'totalItbisT1' => [
            'keywords' => ['TotalITBIS1', 'totalItbisT1'],
            'class' => TotalItbisT1::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'totalItbisT2' => [
            'keywords' => ['TotalITBIS2', 'totalItbisT2'],
            'class' => TotalItbisT2::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'totalItbisT3' => [
            'keywords' => ['TotalITBIS3', 'totalItbisT3'],
            'class' => TotalItbisT3::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'additionalTaxAmount' => [
            'keywords' => ['MontoImpuestoAdicional', 'additionalTaxAmount'],
            'class' => AdditionalTaxAmount::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'additionalTaxesTable' => [
            'keywords' => ['ImpuestosAdicionales', 'additionalTaxesTable'],
            'class' => AdditionalTaxesTable::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'totalAmount' => [
            'keywords' => ['MontoTotal', 'totalAmount'],
            'class' => TotalAmount::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'nonBillableAmount' => [
            'keywords' => ['MontoNoFacturable', 'nonBillableAmount'],
            'class' => NonBillableAmount::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'periodAmount' => [
            'keywords' => ['MontoPeriodo', 'periodAmount'],
            'class' => PeriodAmount::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'previousBalance' => [
            'keywords' => ['SaldoAnterior', 'previousBalance'],
            'class' => PreviousBalance::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'advancedPaymentAmount' => [
            'keywords' => ['MontoAvancePago', 'advancedPaymentAmount'],
            'class' => AdvancedPaymentAmount::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'amountToPay' => [
            'keywords' => ['ValorPagar', 'amountToPay'],
            'class' => AmountToPay::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'totalItbisWithheld' => [
            'keywords' => ['TotalITBISRetenido', 'totalItbisWithheld'],
            'class' => TotalItbisWithheld::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'totalIsrWithheld' => [
            'keywords' => ['TotalISRRetencion', 'totalIsrWithheld'],
            'class' => TotalIsrWithheld::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'totalItbisPerception' => [
            'keywords' => ['TotalITBISPercepcion', 'totalItbisPerception'],
            'class' => TotalItbisPerception::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'totalIsrPerception' => [
            'keywords' => ['TotalISRPercepcion', 'totalIsrPerception'],
            'class' => TotalIsrPerception::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var TotalTaxableAmount|null */
    protected $totalTaxableAmount;

    /** @var TaxableAmountT1|null */
    protected $taxableAmountT1;

    /** @var TaxableAmountT2|null */
    protected $taxableAmountT2;

    /** @var TaxableAmountT3|null */
    protected $taxableAmountT3;

    /** @var ExemptAmount|null */
    protected $exemptAmount;

    /** @var ItbisT1|null */
    protected $itbisT1;

    /** @var ItbisT2|null */
    protected $itbisT2;

    /** @var ItbisT3|null */
    protected $itbisT3;

    /** @var TotalItbis|null */
    protected $totalItbis;

    /** @var TotalItbisT1|null */
    protected $totalItbisT1;

    /** @var TotalItbisT2|null */
    protected $totalItbisT2;

    /** @var TotalItbisT3|null */
    protected $totalItbisT3;

    /** @var AdditionalTaxAmount|null */
    protected $additionalTaxAmount;

    /** @var AdditionalTaxesTable|null */
    protected $additionalTaxesTable;

    /** @var TotalAmount */
    protected $totalAmount;

    /** @var NonBillableAmount|null */
    protected $nonBillableAmount;

    /** @var PeriodAmount|null */
    protected $periodAmount;

    /** @var PreviousBalance|null */
    protected $previousBalance;

    /** @var AdvancedPaymentAmount|null */
    protected $advancedPaymentAmount;

    /** @var AmountToPay|null */
    protected $amountToPay;

    /** @var TotalItbisWithheld|null */
    protected $totalItbisWithheld;

    /** @var TotalIsrWithheld|null */
    protected $totalIsrWithheld;

    /** @var TotalItbisPerception|null */
    protected $totalItbisPerception;

    /** @var TotalIsrPerception|null */
    protected $totalIsrPerception;


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
                case TotalTaxableAmount::NODE_NAME:
                    $this->setTotalTaxableAmount(TotalTaxableAmount::createFromDOMNode($node));
                    break;
                case TaxableAmountT1::NODE_NAME:
                    $this->setTaxableAmountT1(TaxableAmountT1::createFromDOMNode($node));
                    break;
                case TaxableAmountT2::NODE_NAME:
                    $this->setTaxableAmountT2(TaxableAmountT2::createFromDOMNode($node));
                    break;
                case TaxableAmountT3::NODE_NAME:
                    $this->setTaxableAmountT3(TaxableAmountT3::createFromDOMNode($node));
                    break;
                case ExemptAmount::NODE_NAME:
                    $this->setExemptAmount(ExemptAmount::createFromDOMNode($node));
                    break;
                case ItbisT1::NODE_NAME:
                    $this->setItbisT1(ItbisT1::createFromDOMNode($node));
                    break;
                case ItbisT2::NODE_NAME:
                    $this->setItbisT2(ItbisT2::createFromDOMNode($node));
                    break;
                case ItbisT3::NODE_NAME:
                    $this->setItbisT3(ItbisT3::createFromDOMNode($node));
                    break;
                case TotalItbis::NODE_NAME:
                    $this->setTotalItbis(TotalItbis::createFromDOMNode($node));
                    break;
                case TotalItbisT1::NODE_NAME:
                    $this->setTotalItbisT1(TotalItbisT1::createFromDOMNode($node));
                    break;
                case TotalItbisT2::NODE_NAME:
                    $this->setTotalItbisT2(TotalItbisT2::createFromDOMNode($node));
                    break;
                case TotalItbisT3::NODE_NAME:
                    $this->setTotalItbisT3(TotalItbisT3::createFromDOMNode($node));
                    break;
                case AdditionalTaxAmount::NODE_NAME:
                    $this->setAdditionalTaxAmount(AdditionalTaxAmount::createFromDOMNode($node));
                    break;
                case AdditionalTaxesTable::NODE_NAME:
                    $this->setAdditionalTaxesTable(AdditionalTaxesTable::createFromDOMNode($node));
                    break;
                case TotalAmount::NODE_NAME:
                    $this->setTotalAmount(TotalAmount::createFromDOMNode($node));
                    break;
                case NonBillableAmount::NODE_NAME:
                    $this->setNonBillableAmount(NonBillableAmount::createFromDOMNode($node));
                    break;
                case PeriodAmount::NODE_NAME:
                    $this->setPeriodAmount(PeriodAmount::createFromDOMNode($node));
                    break;
                case PreviousBalance::NODE_NAME:
                    $this->setPreviousBalance(PreviousBalance::createFromDOMNode($node));
                    break;
                case AdvancedPaymentAmount::NODE_NAME:
                    $this->setAdvancedPaymentAmount(AdvancedPaymentAmount::createFromDOMNode($node));
                    break;
                case AmountToPay::NODE_NAME:
                    $this->setAmountToPay(AmountToPay::createFromDOMNode($node));
                    break;
                case TotalItbisWithheld::NODE_NAME:
                    $this->setTotalItbisWithheld(TotalItbisWithheld::createFromDOMNode($node));
                    break;
                case TotalIsrWithheld::NODE_NAME:
                    $this->setTotalIsrWithheld(TotalIsrWithheld::createFromDOMNode($node));
                    break;
                case TotalItbisPerception::NODE_NAME:
                    $this->setTotalItbisPerception(TotalItbisPerception::createFromDOMNode($node));
                    break;
                case TotalIsrPerception::NODE_NAME:
                    $this->setTotalIsrPerception(TotalIsrPerception::createFromDOMNode($node));
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

        if ($this->totalTaxableAmount) $node->appendChild($this->totalTaxableAmount->toDOMElement($dom));
        if ($this->taxableAmountT1) $node->appendChild($this->taxableAmountT1->toDOMElement($dom));
        if ($this->taxableAmountT2) $node->appendChild($this->taxableAmountT2->toDOMElement($dom));
        if ($this->taxableAmountT3) $node->appendChild($this->taxableAmountT3->toDOMElement($dom));
        if ($this->exemptAmount) $node->appendChild($this->exemptAmount->toDOMElement($dom));
        if ($this->itbisT1) $node->appendChild($this->itbisT1->toDOMElement($dom));
        if ($this->itbisT2) $node->appendChild($this->itbisT2->toDOMElement($dom));
        if ($this->itbisT3) $node->appendChild($this->itbisT3->toDOMElement($dom));
        if ($this->totalItbis) $node->appendChild($this->totalItbis->toDOMElement($dom));
        if ($this->totalItbisT1) $node->appendChild($this->totalItbisT1->toDOMElement($dom));
        if ($this->totalItbisT2) $node->appendChild($this->totalItbisT2->toDOMElement($dom));
        if ($this->totalItbisT3) $node->appendChild($this->totalItbisT3->toDOMElement($dom));
        if ($this->additionalTaxAmount) $node->appendChild($this->additionalTaxAmount->toDOMElement($dom));
        if ($this->additionalTaxesTable) $node->appendChild($this->additionalTaxesTable->toDOMElement($dom));
        if ($this->totalAmount) $node->appendChild($this->totalAmount->toDOMElement($dom));
        if ($this->nonBillableAmount) $node->appendChild($this->nonBillableAmount->toDOMElement($dom));
        if ($this->periodAmount) $node->appendChild($this->periodAmount->toDOMElement($dom));
        if ($this->previousBalance) $node->appendChild($this->previousBalance->toDOMElement($dom));
        if ($this->advancedPaymentAmount) $node->appendChild($this->advancedPaymentAmount->toDOMElement($dom));
        if ($this->amountToPay) $node->appendChild($this->amountToPay->toDOMElement($dom));
        if ($this->totalItbisWithheld) $node->appendChild($this->totalItbisWithheld->toDOMElement($dom));
        if ($this->totalIsrWithheld) $node->appendChild($this->totalIsrWithheld->toDOMElement($dom));
        if ($this->totalItbisPerception) $node->appendChild($this->totalItbisPerception->toDOMElement($dom));
        if ($this->totalIsrPerception) $node->appendChild($this->totalIsrPerception->toDOMElement($dom));

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

    public function getTotalTaxableAmount(): ?TotalTaxableAmount
    {
        return $this->totalTaxableAmount;
    }

    public function setTotalTaxableAmount(?TotalTaxableAmount $totalTaxableAmount): self
    {
        $this->totalTaxableAmount = $totalTaxableAmount;
        return $this;
    }

    public function getTaxableAmountT1(): ?TaxableAmountT1
    {
        return $this->taxableAmountT1;
    }

    public function setTaxableAmountT1(?TaxableAmountT1 $taxableAmountT1): self
    {
        $this->taxableAmountT1 = $taxableAmountT1;
        return $this;
    }

    public function getTaxableAmountT2(): ?TaxableAmountT2
    {
        return $this->taxableAmountT2;
    }

    public function setTaxableAmountT2(?TaxableAmountT2 $taxableAmountT2): self
    {
        $this->taxableAmountT2 = $taxableAmountT2;
        return $this;
    }

    public function getTaxableAmountT3(): ?TaxableAmountT3
    {
        return $this->taxableAmountT3;
    }

    public function setTaxableAmountT3(?TaxableAmountT3 $taxableAmountT3): self
    {
        $this->taxableAmountT3 = $taxableAmountT3;
        return $this;
    }

    public function getExemptAmount(): ?ExemptAmount
    {
        return $this->exemptAmount;
    }

    public function setExemptAmount(?ExemptAmount $exemptAmount): self
    {
        $this->exemptAmount = $exemptAmount;
        return $this;
    }

    public function getItbisT1(): ?ItbisT1
    {
        return $this->itbisT1;
    }

    public function setItbisT1(?ItbisT1 $itbisT1): self
    {
        $this->itbisT1 = $itbisT1;
        return $this;
    }

    public function getItbisT2(): ?ItbisT2
    {
        return $this->itbisT2;
    }

    public function setItbisT2(?ItbisT2 $itbisT2): self
    {
        $this->itbisT2 = $itbisT2;
        return $this;
    }

    public function getItbisT3(): ?ItbisT3
    {
        return $this->itbisT3;
    }

    public function setItbisT3(?ItbisT3 $itbisT3): self
    {
        $this->itbisT3 = $itbisT3;
        return $this;
    }

    public function getTotalItbis(): ?TotalItbis
    {
        return $this->totalItbis;
    }

    public function setTotalItbis(?TotalItbis $totalItbis): self
    {
        $this->totalItbis = $totalItbis;
        return $this;
    }

    public function getTotalItbisT1(): ?TotalItbisT1
    {
        return $this->totalItbisT1;
    }

    public function setTotalItbisT1(?TotalItbisT1 $totalItbisT1): self
    {
        $this->totalItbisT1 = $totalItbisT1;
        return $this;
    }

    public function getTotalItbisT2(): ?TotalItbisT2
    {
        return $this->totalItbisT2;
    }

    public function setTotalItbisT2(?TotalItbisT2 $totalItbisT2): self
    {
        $this->totalItbisT2 = $totalItbisT2;
        return $this;
    }

    public function getTotalItbisT3(): ?TotalItbisT3
    {
        return $this->totalItbisT3;
    }

    public function setTotalItbisT3(?TotalItbisT3 $totalItbisT3): self
    {
        $this->totalItbisT3 = $totalItbisT3;
        return $this;
    }

    public function getAdditionalTaxAmount(): ?AdditionalTaxAmount
    {
        return $this->additionalTaxAmount;
    }

    public function setAdditionalTaxAmount(?AdditionalTaxAmount $additionalTaxAmount): self
    {
        $this->additionalTaxAmount = $additionalTaxAmount;
        return $this;
    }

    public function getAdditionalTaxesTable(): ?AdditionalTaxesTable
    {
        return $this->additionalTaxesTable;
    }

    public function setAdditionalTaxesTable(?AdditionalTaxesTable $additionalTaxesTable): self
    {
        $this->additionalTaxesTable = $additionalTaxesTable;
        return $this;
    }

    public function getTotalAmount(): ?TotalAmount
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(TotalAmount $totalAmount): self
    {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    public function getNonBillableAmount(): ?NonBillableAmount
    {
        return $this->nonBillableAmount;
    }

    public function setNonBillableAmount(?NonBillableAmount $nonBillableAmount): self
    {
        $this->nonBillableAmount = $nonBillableAmount;
        return $this;
    }

    public function getPeriodAmount(): ?PeriodAmount
    {
        return $this->periodAmount;
    }

    public function setPeriodAmount(?PeriodAmount $periodAmount): self
    {
        $this->periodAmount = $periodAmount;
        return $this;
    }

    public function getPreviousBalance(): ?PreviousBalance
    {
        return $this->previousBalance;
    }

    public function setPreviousBalance(?PreviousBalance $previousBalance): self
    {
        $this->previousBalance = $previousBalance;
        return $this;
    }

    public function getAdvancedPaymentAmount(): ?AdvancedPaymentAmount
    {
        return $this->advancedPaymentAmount;
    }

    public function setAdvancedPaymentAmount(?AdvancedPaymentAmount $advancedPaymentAmount): self
    {
        $this->advancedPaymentAmount = $advancedPaymentAmount;
        return $this;
    }

    public function getAmountToPay(): ?AmountToPay
    {
        return $this->amountToPay;
    }

    public function setAmountToPay(?AmountToPay $amountToPay): self
    {
        $this->amountToPay = $amountToPay;
        return $this;
    }

    public function getTotalItbisWithheld(): ?TotalItbisWithheld
    {
        return $this->totalItbisWithheld;
    }

    public function setTotalItbisWithheld(?TotalItbisWithheld $totalItbisWithheld): self
    {
        $this->totalItbisWithheld = $totalItbisWithheld;
        return $this;
    }

    public function getTotalIsrWithheld(): ?TotalIsrWithheld
    {
        return $this->totalIsrWithheld;
    }

    public function setTotalIsrWithheld(?TotalIsrWithheld $totalIsrWithheld): self
    {
        $this->totalIsrWithheld = $totalIsrWithheld;
        return $this;
    }

    public function getTotalItbisPerception(): ?TotalItbisPerception
    {
        return $this->totalItbisPerception;
    }

    public function setTotalItbisPerception(?TotalItbisPerception $totalItbisPerception): self
    {
        $this->totalItbisPerception = $totalItbisPerception;
        return $this;
    }

    public function getTotalIsrPerception(): ?TotalIsrPerception
    {
        return $this->totalIsrPerception;
    }

    public function setTotalIsrPerception(?TotalIsrPerception $totalIsrPerception): self
    {
        $this->totalIsrPerception = $totalIsrPerception;
        return $this;
    }
}