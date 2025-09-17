<?php

namespace Angle\ECF\Node\ECF10\Header;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\Node\ECF10\Header\DocId\AllInclusiveServiceIndicator;
use Angle\ECF\Node\ECF10\Header\DocId\CreditNoteIndicator;
use Angle\ECF\Node\ECF10\Header\DocId\DateFrom;
use Angle\ECF\Node\ECF10\Header\DocId\DateTo;
use Angle\ECF\Node\ECF10\Header\DocId\DeferredShippingIndicator;
use Angle\ECF\Node\ECF10\Header\DocId\EcfType;
use Angle\ECF\Node\ECF10\Header\DocId\Encf;
use Angle\ECF\Node\ECF10\Header\DocId\IncomeType;
use Angle\ECF\Node\ECF10\Header\DocId\PaymentAccountNumber;
use Angle\ECF\Node\ECF10\Header\DocId\PaymentAccountType;
use Angle\ECF\Node\ECF10\Header\DocId\PaymentBank;
use Angle\ECF\Node\ECF10\Header\DocId\PaymentDeadline;
use Angle\ECF\Node\ECF10\Header\DocId\PaymentMethodsTable;
use Angle\ECF\Node\ECF10\Header\DocId\PaymentTerm;
use Angle\ECF\Node\ECF10\Header\DocId\PaymentType;
use Angle\ECF\Node\ECF10\Header\DocId\SequenceExpirationDate;
use Angle\ECF\Node\ECF10\Header\DocId\TaxedAmountIndicator;
use Angle\ECF\Node\ECF10\Header\DocId\TotalPages;
use DateTime;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static DocId createFromDOMNode(DOMNode $node)
 */
class DocId extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "IdDoc";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'ecfType' => [
            'keywords'  => ['TipoeCF', 'ecfType'],
            'class'     => EcfType::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'encf' => [
            'keywords'  => ['eNCF', 'encf'],
            'class'     => Encf::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'sequenceExpirationDate' => [
            'keywords'  => ['FechaVencimientoSecuencia', 'sequenceExpirationDate'],
            'class'     => SequenceExpirationDate::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'creditNoteIndicator' => [
            'keywords'  => ['IndicadorNotaCredito', 'creditNoteIndicator'],
            'class'     => CreditNoteIndicator::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'deferredShippingIndicator' => [
            'keywords'  => ['IndicadorEnvioDiferido', 'deferredShippingIndicator'],
            'class'     => DeferredShippingIndicator::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'taxedAmountIndicator' => [
            'keywords'  => ['IndicadorMontoGravado', 'taxedAmountIndicator'],
            'class'     => TaxedAmountIndicator::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'allInclusiveServiceIndicator' => [
            'keywords'  => ['IndicadorServicioTodoIncluido', 'allInclusiveServiceIndicator'],
            'class'     => AllInclusiveServiceIndicator::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'incomeType' => [
            'keywords'  => ['TipoIngresos', 'incomeType'],
            'class'     => IncomeType::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'paymentType' => [
            'keywords'  => ['TipoPago', 'paymentType'],
            'class'     => PaymentType::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'paymentDeadline' => [
            'keywords'  => ['FechaLimitePago', 'paymentDeadline'],
            'class'     => PaymentDeadline::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'paymentTerm' => [
            'keywords'  => ['TerminoPago', 'paymentTerm'],
            'class'     => PaymentTerm::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'paymentMethodsTable' => [
            'keywords'  => ['TablaFormasPago', 'paymentMethodsTable'],
            'class'     => PaymentMethodsTable::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'paymentAccountType' => [
            'keywords'  => ['TipoCuentaPago', 'paymentAccountType'],
            'class'     => PaymentAccountType::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'paymentAccountNumber' => [
            'keywords'  => ['NumeroCuentaPago', 'paymentAccountNumber'],
            'class'     => PaymentAccountNumber::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'paymentBank' => [
            'keywords'  => ['BancoPago', 'paymentBank'],
            'class'     => PaymentBank::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'dateFrom' => [
            'keywords'  => ['FechaDesde', 'dateFrom'],
            'class'     => DateFrom::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'dateTo' => [
            'keywords'  => ['FechaHasta', 'dateTo'],
            'class'     => DateTo::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'totalPages' => [
            'keywords'  => ['TotalPaginas', 'totalPages'],
            'class'     => TotalPages::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var EcfType */
    protected $ecfType;

    /** @var Encf */
    protected $encf;

    /** @var SequenceExpirationDate|null */
    protected $sequenceExpirationDate;

    /** @var CreditNoteIndicator|null */
    protected $creditNoteIndicator;

    /** @var DeferredShippingIndicator|null */
    protected $deferredShippingIndicator;

    /** @var TaxedAmountIndicator|null */
    protected $taxedAmountIndicator;

    /** @var AllInclusiveServiceIndicator|null */
    protected $allInclusiveServiceIndicator;

    /** @var IncomeType */
    protected $incomeType;

    /** @var PaymentType */
    protected $paymentType;

    /** @var PaymentDeadline|null */
    protected $paymentDeadline;

    /** @var PaymentTerm|null */
    protected $paymentTerm;

    /** @var PaymentMethodsTable|null */
    protected $paymentMethodsTable;

    /** @var PaymentAccountType|null */
    protected $paymentAccountType;

    /** @var PaymentAccountNumber|null */
    protected $paymentAccountNumber;

    /** @var PaymentBank|null */
    protected $paymentBank;

    /** @var DateFrom|null */
    protected $dateFrom;

    /** @var DateTo|null */
    protected $dateTo;

    /** @var TotalPages|null */
    protected $totalPages;

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
                case EcfType::NODE_NAME:
                    $this->setEcfType(EcfType::createFromDOMNode($node));
                    break;
                case Encf::NODE_NAME:
                    $this->setEncf(Encf::createFromDOMNode($node));
                    break;
                case SequenceExpirationDate::NODE_NAME:
                    $this->setSequenceExpirationDate(SequenceExpirationDate::createFromDOMNode($node));
                    break;
                case CreditNoteIndicator::NODE_NAME:
                    $this->setCreditNoteIndicator(CreditNoteIndicator::createFromDOMNode($node));
                    break;
                case DeferredShippingIndicator::NODE_NAME:
                    $this->setDeferredShippingIndicator(DeferredShippingIndicator::createFromDOMNode($node));
                    break;
                case TaxedAmountIndicator::NODE_NAME:
                    $this->setTaxedAmountIndicator(TaxedAmountIndicator::createFromDOMNode($node));
                    break;
                case AllInclusiveServiceIndicator::NODE_NAME:
                    $this->setAllInclusiveServiceIndicator(AllInclusiveServiceIndicator::createFromDOMNode($node));
                    break;
                case IncomeType::NODE_NAME:
                    $this->setIncomeType(IncomeType::createFromDOMNode($node));
                    break;
                case PaymentType::NODE_NAME:
                    $this->setPaymentType(PaymentType::createFromDOMNode($node));
                    break;
                case PaymentDeadline::NODE_NAME:
                    $this->setPaymentDeadline(PaymentDeadline::createFromDOMNode($node));
                    break;
                case PaymentTerm::NODE_NAME:
                    $this->setPaymentTerm(PaymentTerm::createFromDOMNode($node));
                    break;
                case PaymentMethodsTable::NODE_NAME:
                    $this->setPaymentMethodsTable(PaymentMethodsTable::createFromDOMNode($node));
                    break;
                case PaymentAccountType::NODE_NAME:
                    $this->setPaymentAccountType(PaymentAccountType::createFromDOMNode($node));
                    break;
                case PaymentAccountNumber::NODE_NAME:
                    $this->setPaymentAccountNumber(PaymentAccountNumber::createFromDOMNode($node));
                    break;
                case PaymentBank::NODE_NAME:
                    $this->setPaymentBank(PaymentBank::createFromDOMNode($node));
                    break;
                case DateFrom::NODE_NAME:
                    $this->setDateFrom(DateFrom::createFromDOMNode($node));
                    break;
                case DateTo::NODE_NAME:
                    $this->setDateTo(DateTo::createFromDOMNode($node));
                    break;
                case TotalPages::NODE_NAME:
                    $this->setTotalPages(TotalPages::createFromDOMNode($node));
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

        // EcfType
        if ($this->ecfType) {
            $node->appendChild($this->ecfType->toDOMElement($dom));
        }

        // Encf
        if ($this->encf) {
            $node->appendChild($this->encf->toDOMElement($dom));
        }

        // SequenceExpirationDate
        if ($this->sequenceExpirationDate) {
            $node->appendChild($this->sequenceExpirationDate->toDOMElement($dom));
        }

        // CreditNoteIndicator
        if ($this->creditNoteIndicator) {
            $node->appendChild($this->creditNoteIndicator->toDOMElement($dom));
        }

        // DeferredShippingIndicator
        if ($this->deferredShippingIndicator) {
            $node->appendChild($this->deferredShippingIndicator->toDOMElement($dom));
        }

        // TaxedAmountIndicator
        if ($this->taxedAmountIndicator) {
            $node->appendChild($this->taxedAmountIndicator->toDOMElement($dom));
        }

        // AllInclusiveServiceIndicator
        if ($this->allInclusiveServiceIndicator) {
            $node->appendChild($this->allInclusiveServiceIndicator->toDOMElement($dom));
        }

        // IncomeType
        if ($this->incomeType) {
            $node->appendChild($this->incomeType->toDOMElement($dom));
        }

        // PaymentType
        if ($this->paymentType) {
            $node->appendChild($this->paymentType->toDOMElement($dom));
        }

        // PaymentDeadline
        if ($this->paymentDeadline) {
            $node->appendChild($this->paymentDeadline->toDOMElement($dom));
        }

        // PaymentTerm
        if ($this->paymentTerm) {
            $node->appendChild($this->paymentTerm->toDOMElement($dom));
        }

        // PaymentMethodsTable
        if ($this->paymentMethodsTable) {
            $node->appendChild($this->paymentMethodsTable->toDOMElement($dom));
        }

        // PaymentAccountType
        if ($this->paymentAccountType) {
            $node->appendChild($this->paymentAccountType->toDOMElement($dom));
        }

        // PaymentAccountNumber
        if ($this->paymentAccountNumber) {
            $node->appendChild($this->paymentAccountNumber->toDOMElement($dom));
        }

        // PaymentBank
        if ($this->paymentBank) {
            $node->appendChild($this->paymentBank->toDOMElement($dom));
        }

        // DateFrom
        if ($this->dateFrom) {
            $node->appendChild($this->dateFrom->toDOMElement($dom));
        }

        // DateTo
        if ($this->dateTo) {
            $node->appendChild($this->dateTo->toDOMElement($dom));
        }

        // TotalPages
        if ($this->totalPages) {
            $node->appendChild($this->totalPages->toDOMElement($dom));
        }

        return $node;    }


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

    public function getEcfType(): ?EcfType
    {
        return $this->ecfType;
    }

    public function setEcfType(EcfType $ecfType): self
    {
        $this->ecfType = $ecfType;
        return $this;
    }

    public function getEncf(): ?Encf
    {
        return $this->encf;
    }

    public function setEncf(Encf $encf): self
    {
        $this->encf = $encf;
        return $this;
    }

    public function getSequenceExpirationDate(): ?SequenceExpirationDate
    {
        return $this->sequenceExpirationDate;
    }

    public function setSequenceExpirationDate(?SequenceExpirationDate $sequenceExpirationDate): self
    {
        $this->sequenceExpirationDate = $sequenceExpirationDate;
        return $this;
    }

    public function getCreditNoteIndicator(): ?CreditNoteIndicator
    {
        return $this->creditNoteIndicator;
    }

    public function setCreditNoteIndicator(?CreditNoteIndicator $creditNoteIndicator): self
    {
        $this->creditNoteIndicator = $creditNoteIndicator;
        return $this;
    }

    public function getDeferredShippingIndicator(): ?DeferredShippingIndicator
    {
        return $this->deferredShippingIndicator;
    }

    public function setDeferredShippingIndicator(?DeferredShippingIndicator $deferredShippingIndicator): self
    {
        $this->deferredShippingIndicator = $deferredShippingIndicator;
        return $this;
    }

    public function getTaxedAmountIndicator(): ?TaxedAmountIndicator
    {
        return $this->taxedAmountIndicator;
    }

    public function setTaxedAmountIndicator(?TaxedAmountIndicator $taxedAmountIndicator): self
    {
        $this->taxedAmountIndicator = $taxedAmountIndicator;
        return $this;
    }

    public function getAllInclusiveServiceIndicator(): ?AllInclusiveServiceIndicator
    {
        return $this->allInclusiveServiceIndicator;
    }

    public function setAllInclusiveServiceIndicator(?AllInclusiveServiceIndicator $allInclusiveServiceIndicator): self
    {
        $this->allInclusiveServiceIndicator = $allInclusiveServiceIndicator;
        return $this;
    }

    public function getIncomeType(): ?IncomeType
    {
        return $this->incomeType;
    }

    public function setIncomeType(IncomeType $incomeType): self
    {
        $this->incomeType = $incomeType;
        return $this;
    }

    public function getPaymentType(): ?PaymentType
    {
        return $this->paymentType;
    }

    public function setPaymentType(PaymentType $paymentType): self
    {
        $this->paymentType = $paymentType;
        return $this;
    }

    public function getPaymentDeadline(): ?PaymentDeadline
    {
        return $this->paymentDeadline;
    }

    public function setPaymentDeadline(?PaymentDeadline $paymentDeadline): self
    {
        $this->paymentDeadline = $paymentDeadline;
        return $this;
    }

    public function getPaymentTerm(): ?PaymentTerm
    {
        return $this->paymentTerm;
    }

    public function setPaymentTerm(?PaymentTerm $paymentTerm): self
    {
        $this->paymentTerm = $paymentTerm;
        return $this;
    }

    public function getPaymentMethodsTable(): ?PaymentMethodsTable
    {
        return $this->paymentMethodsTable;
    }

    public function setPaymentMethodsTable(?PaymentMethodsTable $paymentMethodsTable): self
    {
        $this->paymentMethodsTable = $paymentMethodsTable;
        return $this;
    }

    public function getPaymentAccountType(): ?PaymentAccountType
    {
        return $this->paymentAccountType;
    }

    public function setPaymentAccountType(?PaymentAccountType $paymentAccountType): self
    {
        $this->paymentAccountType = $paymentAccountType;
        return $this;
    }

    public function getPaymentAccountNumber(): ?PaymentAccountNumber
    {
        return $this->paymentAccountNumber;
    }

    public function setPaymentAccountNumber(?PaymentAccountNumber $paymentAccountNumber): self
    {
        $this->paymentAccountNumber = $paymentAccountNumber;
        return $this;
    }

    public function getPaymentBank(): ?PaymentBank
    {
        return $this->paymentBank;
    }

    public function setPaymentBank(?PaymentBank $paymentBank): self
    {
        $this->paymentBank = $paymentBank;
        return $this;
    }

    public function getDateFrom(): ?DateFrom
    {
        return $this->dateFrom;
    }

    public function setDateFrom(?DateFrom $dateFrom): self
    {
        $this->dateFrom = $dateFrom;
        return $this;
    }

    public function getDateTo(): ?DateTo
    {
        return $this->dateTo;
    }

    public function setDateTo(?DateTo $dateTo): self
    {
        $this->dateTo = $dateTo;
        return $this;
    }

    public function getTotalPages(): ?TotalPages
    {
        return $this->totalPages;
    }

    public function setTotalPages(?TotalPages $totalPages): self
    {
        $this->totalPages = $totalPages;
        return $this;
    }

}