<?php

namespace Angle\ECF\Node\ECF10\Pagination;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;

use Angle\ECF\Node\ECF10\Pagination\Page\PageNumber;
use Angle\ECF\Node\ECF10\Pagination\Page\LineFrom;
use Angle\ECF\Node\ECF10\Pagination\Page\LineTo;
use Angle\ECF\Node\ECF10\Pagination\Page\PageTotalTaxableAmount;
use Angle\ECF\Node\ECF10\Pagination\Page\PageTaxableAmountT1;
use Angle\ECF\Node\ECF10\Pagination\Page\PageTaxableAmountT2;
use Angle\ECF\Node\ECF10\Pagination\Page\PageTaxableAmountT3;
use Angle\ECF\Node\ECF10\Pagination\Page\PageExemptAmount;
use Angle\ECF\Node\ECF10\Pagination\Page\PageTotalItbis;
use Angle\ECF\Node\ECF10\Pagination\Page\PageItbisT1;
use Angle\ECF\Node\ECF10\Pagination\Page\PageItbisT2;
use Angle\ECF\Node\ECF10\Pagination\Page\PageItbisT3;
use Angle\ECF\Node\ECF10\Pagination\Page\PageAdditionalTaxAmount;
use Angle\ECF\Node\ECF10\Pagination\Page\SubtotalAdditionalTax;
use Angle\ECF\Node\ECF10\Pagination\Page\PageSubtotalAmount;
use Angle\ECF\Node\ECF10\Pagination\Page\PageNonBillableAmount;


use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static Page createFromDOMNode(DOMNode $node)
 */
class Page extends ECFNode
{
    const NODE_NAME = "Pagina";
    protected static $children = [
        'pageNumber' => ['keywords' => ['PaginaNo', 'pageNumber'], 'class' => PageNumber::class, 'type' => ECFNode::CHILD_UNIQUE],
        'lineFrom' => ['keywords' => ['NoLineaDesde', 'lineFrom'], 'class' => LineFrom::class, 'type' => ECFNode::CHILD_UNIQUE],
        'lineTo' => ['keywords' => ['NoLineaHasta', 'lineTo'], 'class' => LineTo::class, 'type' => ECFNode::CHILD_UNIQUE],
        'pageTotalTaxableAmount' => ['keywords' => ['SubtotalMontoGravadoPagina', 'pageTotalTaxableAmount'], 'class' => PageTotalTaxableAmount::class, 'type' => ECFNode::CHILD_UNIQUE],
        'pageTaxableAmountT1' => ['keywords' => ['SubtotalMontoGravado1Pagina', 'pageTaxableAmountT1'], 'class' => PageTaxableAmountT1::class, 'type' => ECFNode::CHILD_UNIQUE],
        'pageTaxableAmountT2' => ['keywords' => ['SubtotalMontoGravado2Pagina', 'pageTaxableAmountT2'], 'class' => PageTaxableAmountT2::class, 'type' => ECFNode::CHILD_UNIQUE],
        'pageTaxableAmountT3' => ['keywords' => ['SubtotalMontoGravado3Pagina', 'pageTaxableAmountT3'], 'class' => PageTaxableAmountT3::class, 'type' => ECFNode::CHILD_UNIQUE],
        'pageExemptAmount' => ['keywords' => ['SubtotalExentoPagina', 'pageExemptAmount'], 'class' => PageExemptAmount::class, 'type' => ECFNode::CHILD_UNIQUE],
        'pageTotalItbis' => ['keywords' => ['SubtotalItbisPagina', 'pageTotalItbis'], 'class' => PageTotalItbis::class, 'type' => ECFNode::CHILD_UNIQUE],
        'pageItbisT1' => ['keywords' => ['SubtotalItbis1Pagina', 'pageItbisT1'], 'class' => PageItbisT1::class, 'type' => ECFNode::CHILD_UNIQUE],
        'pageItbisT2' => ['keywords' => ['SubtotalItbis2Pagina', 'pageItbisT2'], 'class' => PageItbisT2::class, 'type' => ECFNode::CHILD_UNIQUE],
        'pageItbisT3' => ['keywords' => ['SubtotalItbis3Pagina', 'pageItbisT3'], 'class' => PageItbisT3::class, 'type' => ECFNode::CHILD_UNIQUE],
        'pageAdditionalTaxAmount' => ['keywords' => ['SubtotalImpuestoAdicionalPagina', 'pageAdditionalTaxAmount'], 'class' => PageAdditionalTaxAmount::class, 'type' => ECFNode::CHILD_UNIQUE],
        'subtotalAdditionalTax' => ['keywords' => ['SubtotalImpuestoAdicional', 'subtotalAdditionalTax'], 'class' => SubtotalAdditionalTax::class, 'type' => ECFNode::CHILD_UNIQUE],
        'pageSubtotalAmount' => ['keywords' => ['MontoSubtotalPagina', 'pageSubtotalAmount'], 'class' => PageSubtotalAmount::class, 'type' => ECFNode::CHILD_UNIQUE],
        'pageNonBillableAmount' => ['keywords' => ['SubtotalMontoNoFacturablePagina', 'pageNonBillableAmount'], 'class' => PageNonBillableAmount::class, 'type' => ECFNode::CHILD_UNIQUE],
    ];

    protected $pageNumber;
    protected $lineFrom;
    protected $lineTo;
    protected $pageTotalTaxableAmount;
    protected $pageTaxableAmountT1;
    protected $pageTaxableAmountT2;
    protected $pageTaxableAmountT3;
    protected $pageExemptAmount;
    protected $pageTotalItbis;
    protected $pageItbisT1;
    protected $pageItbisT2;
    protected $pageItbisT3;
    protected $pageAdditionalTaxAmount;
    protected $subtotalAdditionalTax;
    protected $pageSubtotalAmount;
    protected $pageNonBillableAmount;

    public function setChildrenFromDOMNodes(array $children): void
    {
        foreach ($children as $node) {
            if ($node instanceof DOMText) continue;
            switch ($node->localName) {
                case PageNumber::NODE_NAME: $this->setPageNumber(PageNumber::createFromDOMNode($node)); break;
                case LineFrom::NODE_NAME: $this->setLineFrom(LineFrom::createFromDOMNode($node)); break;
                case LineTo::NODE_NAME: $this->setLineTo(LineTo::createFromDOMNode($node)); break;
                case PageTotalTaxableAmount::NODE_NAME: $this->setPageTotalTaxableAmount(PageTotalTaxableAmount::createFromDOMNode($node)); break;
                case PageTaxableAmountT1::NODE_NAME: $this->setPageTaxableAmountT1(PageTaxableAmountT1::createFromDOMNode($node)); break;
                case PageTaxableAmountT2::NODE_NAME: $this->setPageTaxableAmountT2(PageTaxableAmountT2::createFromDOMNode($node)); break;
                case PageTaxableAmountT3::NODE_NAME: $this->setPageTaxableAmountT3(PageTaxableAmountT3::createFromDOMNode($node)); break;
                case PageExemptAmount::NODE_NAME: $this->setPageExemptAmount(PageExemptAmount::createFromDOMNode($node)); break;
                case PageTotalItbis::NODE_NAME: $this->setPageTotalItbis(PageTotalItbis::createFromDOMNode($node)); break;
                case PageItbisT1::NODE_NAME: $this->setPageItbisT1(PageItbisT1::createFromDOMNode($node)); break;
                case PageItbisT2::NODE_NAME: $this->setPageItbisT2(PageItbisT2::createFromDOMNode($node)); break;
                case PageItbisT3::NODE_NAME: $this->setPageItbisT3(PageItbisT3::createFromDOMNode($node)); break;
                case PageAdditionalTaxAmount::NODE_NAME: $this->setPageAdditionalTaxAmount(PageAdditionalTaxAmount::createFromDOMNode($node)); break;
                case SubtotalAdditionalTax::NODE_NAME: $this->setSubtotalAdditionalTax(SubtotalAdditionalTax::createFromDOMNode($node)); break;
                case PageSubtotalAmount::NODE_NAME: $this->setPageSubtotalAmount(PageSubtotalAmount::createFromDOMNode($node)); break;
                case PageNonBillableAmount::NODE_NAME: $this->setPageNonBillableAmount(PageNonBillableAmount::createFromDOMNode($node)); break;
            }
        }
    }

    public function toDOMElement(DOMDocument $dom): DOMElement
    {
        $node = $dom->createElement(self::NODE_NAME);
        if ($this->pageNumber) $node->appendChild($this->pageNumber->toDOMElement($dom));
        if ($this->lineFrom) $node->appendChild($this->lineFrom->toDOMElement($dom));
        if ($this->lineTo) $node->appendChild($this->lineTo->toDOMElement($dom));
        if ($this->pageTotalTaxableAmount) $node->appendChild($this->pageTotalTaxableAmount->toDOMElement($dom));
        if ($this->pageTaxableAmountT1) $node->appendChild($this->pageTaxableAmountT1->toDOMElement($dom));
        if ($this->pageTaxableAmountT2) $node->appendChild($this->pageTaxableAmountT2->toDOMElement($dom));
        if ($this->pageTaxableAmountT3) $node->appendChild($this->pageTaxableAmountT3->toDOMElement($dom));
        if ($this->pageExemptAmount) $node->appendChild($this->pageExemptAmount->toDOMElement($dom));
        if ($this->pageTotalItbis) $node->appendChild($this->pageTotalItbis->toDOMElement($dom));
        if ($this->pageItbisT1) $node->appendChild($this->pageItbisT1->toDOMElement($dom));
        if ($this->pageItbisT2) $node->appendChild($this->pageItbisT2->toDOMElement($dom));
        if ($this->pageItbisT3) $node->appendChild($this->pageItbisT3->toDOMElement($dom));
        if ($this->pageAdditionalTaxAmount) $node->appendChild($this->pageAdditionalTaxAmount->toDOMElement($dom));
        if ($this->subtotalAdditionalTax) $node->appendChild($this->subtotalAdditionalTax->toDOMElement($dom));
        if ($this->pageSubtotalAmount) $node->appendChild($this->pageSubtotalAmount->toDOMElement($dom));
        if ($this->pageNonBillableAmount) $node->appendChild($this->pageNonBillableAmount->toDOMElement($dom));
        return $node;
    }

    public function validate(): bool { return true; }

    //<editor-fold desc="Getters and Setters">
    public function getPageNumber(): ?PageNumber { return $this->pageNumber; }
    public function setPageNumber(?PageNumber $pageNumber): self { $this->pageNumber = $pageNumber; return $this; }
    public function getLineFrom(): ?LineFrom { return $this->lineFrom; }
    public function setLineFrom(?LineFrom $lineFrom): self { $this->lineFrom = $lineFrom; return $this; }
    public function getLineTo(): ?LineTo { return $this->lineTo; }
    public function setLineTo(?LineTo $lineTo): self { $this->lineTo = $lineTo; return $this; }
    public function getPageTotalTaxableAmount(): ?PageTotalTaxableAmount { return $this->pageTotalTaxableAmount; }
    public function setPageTotalTaxableAmount(?PageTotalTaxableAmount $pageTotalTaxableAmount): self { $this->pageTotalTaxableAmount = $pageTotalTaxableAmount; return $this; }
    public function getPageTaxableAmountT1(): ?PageTaxableAmountT1 { return $this->pageTaxableAmountT1; }
    public function setPageTaxableAmountT1(?PageTaxableAmountT1 $pageTaxableAmountT1): self { $this->pageTaxableAmountT1 = $pageTaxableAmountT1; return $this; }
    public function getPageTaxableAmountT2(): ?PageTaxableAmountT2 { return $this->pageTaxableAmountT2; }
    public function setPageTaxableAmountT2(?PageTaxableAmountT2 $pageTaxableAmountT2): self { $this->pageTaxableAmountT2 = $pageTaxableAmountT2; return $this; }
    public function getPageTaxableAmountT3(): ?PageTaxableAmountT3 { return $this->pageTaxableAmountT3; }
    public function setPageTaxableAmountT3(?PageTaxableAmountT3 $pageTaxableAmountT3): self { $this->pageTaxableAmountT3 = $pageTaxableAmountT3; return $this; }
    public function getPageExemptAmount(): ?PageExemptAmount { return $this->pageExemptAmount; }
    public function setPageExemptAmount(?PageExemptAmount $pageExemptAmount): self { $this->pageExemptAmount = $pageExemptAmount; return $this; }
    public function getPageTotalItbis(): ?PageTotalItbis { return $this->pageTotalItbis; }
    public function setPageTotalItbis(?PageTotalItbis $pageTotalItbis): self { $this->pageTotalItbis = $pageTotalItbis; return $this; }
    public function getPageItbisT1(): ?PageItbisT1 { return $this->pageItbisT1; }
    public function setPageItbisT1(?PageItbisT1 $pageItbisT1): self { $this->pageItbisT1 = $pageItbisT1; return $this; }
    public function getPageItbisT2(): ?PageItbisT2 { return $this->pageItbisT2; }
    public function setPageItbisT2(?PageItbisT2 $pageItbisT2): self { $this->pageItbisT2 = $pageItbisT2; return $this; }
    public function getPageItbisT3(): ?PageItbisT3 { return $this->pageItbisT3; }
    public function setPageItbisT3(?PageItbisT3 $pageItbisT3): self { $this->pageItbisT3 = $pageItbisT3; return $this; }
    public function getPageAdditionalTaxAmount(): ?PageAdditionalTaxAmount { return $this->pageAdditionalTaxAmount; }
    public function setPageAdditionalTaxAmount(?PageAdditionalTaxAmount $pageAdditionalTaxAmount): self { $this->pageAdditionalTaxAmount = $pageAdditionalTaxAmount; return $this; }
    public function getSubtotalAdditionalTax(): ?SubtotalAdditionalTax { return $this->subtotalAdditionalTax; }
    public function setSubtotalAdditionalTax(?SubtotalAdditionalTax $subtotalAdditionalTax): self { $this->subtotalAdditionalTax = $subtotalAdditionalTax; return $this; }
    public function getPageSubtotalAmount(): ?PageSubtotalAmount { return $this->pageSubtotalAmount; }
    public function setPageSubtotalAmount(?PageSubtotalAmount $pageSubtotalAmount): self { $this->pageSubtotalAmount = $pageSubtotalAmount; return $this; }
    public function getPageNonBillableAmount(): ?PageNonBillableAmount { return $this->pageNonBillableAmount; }
    public function setPageNonBillableAmount(?PageNonBillableAmount $pageNonBillableAmount): self { $this->pageNonBillableAmount = $pageNonBillableAmount; return $this; }
    //</editor-fold>
}