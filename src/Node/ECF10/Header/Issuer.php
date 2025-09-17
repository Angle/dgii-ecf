<?php

namespace Angle\ECF\Node\ECF10\Header;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;

use Angle\ECF\Node\ECF10\Header\Issuer\IssuerRNC;
use Angle\ECF\Node\ECF10\Header\Issuer\CompanyName;
use Angle\ECF\Node\ECF10\Header\Issuer\LegalName;
use Angle\ECF\Node\ECF10\Header\Issuer\Branch;
use Angle\ECF\Node\ECF10\Header\Issuer\IssuerAddress;
use Angle\ECF\Node\ECF10\Header\Issuer\Municipality;
use Angle\ECF\Node\ECF10\Header\Issuer\Province;
use Angle\ECF\Node\ECF10\Header\Issuer\IssuerPhoneTable;
use Angle\ECF\Node\ECF10\Header\Issuer\IssuerEmail;
use Angle\ECF\Node\ECF10\Header\Issuer\Website;
use Angle\ECF\Node\ECF10\Header\Issuer\EconomicActivity;
use Angle\ECF\Node\ECF10\Header\Issuer\VendorCode;
use Angle\ECF\Node\ECF10\Header\Issuer\InternalInvoiceNumber;
use Angle\ECF\Node\ECF10\Header\Issuer\InternalOrderNumber;
use Angle\ECF\Node\ECF10\Header\Issuer\SalesZone;
use Angle\ECF\Node\ECF10\Header\Issuer\SalesRoute;
use Angle\ECF\Node\ECF10\Header\Issuer\IssuerAdditionalInformation;
use Angle\ECF\Node\ECF10\Header\Issuer\IssueDate;


use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static Issuer createFromDOMNode(DOMNode $node)
 */
class Issuer extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "Emisor";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'issuerRnc' => [
            'keywords' => ['RNCEmisor', 'issuerRnc'],
            'class' => IssuerRNC::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'companyName' => [
            'keywords' => ['RazonSocialEmisor', 'companyName'],
            'class' => CompanyName::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'legalName' => [
            'keywords' => ['NombreComercial', 'legalName'],
            'class' => LegalName::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'branch' => [
            'keywords' => ['Sucursal', 'branch'],
            'class' => Branch::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'issuerAddress' => [
            'keywords' => ['DireccionEmisor', 'issuerAddress'],
            'class' => IssuerAddress::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'municipality' => [
            'keywords' => ['Municipio', 'municipality'],
            'class' => Municipality::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'province' => [
            'keywords' => ['Provincia', 'province'],
            'class' => Province::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'phoneTable' => [
            'keywords' => ['TablaTelefonoEmisor', 'phoneTable'],
            'class' => IssuerPhoneTable::class,
            'type' => ECFNode::CHILD_ARRAY,
        ],
        'issuerEmail' => [
            'keywords' => ['CorreoEmisor', 'issuerEmail'],
            'class' => IssuerEmail::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'website' => [
            'keywords' => ['WebSite', 'website'],
            'class' => Website::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'economicActivity' => [
            'keywords' => ['ActividadEconomica', 'economicActivity'],
            'class' => EconomicActivity::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'vendorCode' => [
            'keywords' => ['CodigoVendedor', 'vendorCode'],
            'class' => VendorCode::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'internalInvoiceNumber' => [
            'keywords' => ['NumeroFacturaInterna', 'internalInvoiceNumber'],
            'class' => InternalInvoiceNumber::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'internalOrderNumber' => [
            'keywords' => ['NumeroPedidoInterno', 'internalOrderNumber'],
            'class' => InternalOrderNumber::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'salesZone' => [
            'keywords' => ['ZonaVenta', 'salesZone'],
            'class' => SalesZone::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'salesRoute' => [
            'keywords' => ['RutaVenta', 'salesRoute'],
            'class' => SalesRoute::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'issuerAdditionalInformation' => [
            'keywords' => ['InformacionAdicionalEmisor', 'issuerAdditionalInformation'],
            'class' => IssuerAdditionalInformation::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'issueDate' => [
            'keywords' => ['FechaEmision', 'issueDate'],
            'class' => IssueDate::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var IssuerRNC */
    protected $issuerRnc;

    /** @var CompanyName */
    protected $companyName;

    /** @var LegalName|null */
    protected $legalName;

    /** @var Branch|null */
    protected $branch;

    /** @var IssuerAddress */
    protected $issuerAddress;

    /** @var Municipality|null */
    protected $municipality;

    /** @var Province|null */
    protected $province;

    /** @var IssuerPhoneTable|null */
    protected $phoneTable;

    /** @var IssuerEmail|null */
    protected $issuerEmail;

    /** @var Website|null */
    protected $website;

    /** @var EconomicActivity|null */
    protected $economicActivity;

    /** @var VendorCode|null */
    protected $vendorCode;

    /** @var InternalInvoiceNumber|null */
    protected $internalInvoiceNumber;

    /** @var InternalOrderNumber|null */
    protected $internalOrderNumber;

    /** @var SalesZone|null */
    protected $salesZone;

    /** @var SalesRoute|null */
    protected $salesRoute;

    /** @var IssuerAdditionalInformation|null */
    protected $issuerAdditionalInformation;

    /** @var IssueDate */
    protected $issueDate;


    #########################
    ##     CONSTRUCTOR     ##
    #########################

    /**
     * @param DOMNode[] $children
     * @throws ECFException
     */
    public function setChildrenFromDOMNodes(array $children): void
    {
        foreach ($children as $node) {
            if ($node instanceof DOMText) {
                continue;
            }

            switch ($node->localName) {
                case IssuerRNC::NODE_NAME:
                    $this->setIssuerRNC(IssuerRNC::createFromDOMNode($node));
                    break;
                case CompanyName::NODE_NAME:
                    $this->setCompanyName(CompanyName::createFromDOMNode($node));
                    break;
                case LegalName::NODE_NAME:
                    $this->setLegalName(LegalName::createFromDOMNode($node));
                    break;
                case Branch::NODE_NAME:
                    $this->setBranch(Branch::createFromDOMNode($node));
                    break;
                case IssuerAddress::NODE_NAME:
                    $this->setIssuerAddress(IssuerAddress::createFromDOMNode($node));
                    break;
                case Municipality::NODE_NAME:
                    $this->setMunicipality(Municipality::createFromDOMNode($node));
                    break;
                case Province::NODE_NAME:
                    $this->setProvince(Province::createFromDOMNode($node));
                    break;
                case IssuerPhoneTable::NODE_NAME:
                    $this->setIssuerPhoneTable(IssuerPhoneTable::createFromDOMNode($node));
                    break;
                case IssuerEmail::NODE_NAME:
                    $this->setIssuerEmail(IssuerEmail::createFromDOMNode($node));
                    break;
                case Website::NODE_NAME:
                    $this->setWebsite(Website::createFromDOMNode($node));
                    break;
                case EconomicActivity::NODE_NAME:
                    $this->setEconomicActivity(EconomicActivity::createFromDOMNode($node));
                    break;
                case VendorCode::NODE_NAME:
                    $this->setVendorCode(VendorCode::createFromDOMNode($node));
                    break;
                case InternalInvoiceNumber::NODE_NAME:
                    $this->setInternalInvoiceNumber(InternalInvoiceNumber::createFromDOMNode($node));
                    break;
                case InternalOrderNumber::NODE_NAME:
                    $this->setInternalOrderNumber(InternalOrderNumber::createFromDOMNode($node));
                    break;
                case SalesZone::NODE_NAME:
                    $this->setSalesZone(SalesZone::createFromDOMNode($node));
                    break;
                case SalesRoute::NODE_NAME:
                    $this->setSalesRoute(SalesRoute::createFromDOMNode($node));
                    break;
                case IssuerAdditionalInformation::NODE_NAME:
                    $this->setIssuerAdditionalInformation(IssuerAdditionalInformation::createFromDOMNode($node));
                    break;
                case IssueDate::NODE_NAME:
                    $this->setIssueDate(IssueDate::createFromDOMNode($node));
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

        if ($this->issuerRnc) $node->appendChild($this->issuerRnc->toDOMElement($dom));
        if ($this->companyName) $node->appendChild($this->companyName->toDOMElement($dom));
        if ($this->legalName) $node->appendChild($this->legalName->toDOMElement($dom));
        if ($this->branch) $node->appendChild($this->branch->toDOMElement($dom));
        if ($this->issuerAddress) $node->appendChild($this->issuerAddress->toDOMElement($dom));
        if ($this->municipality) $node->appendChild($this->municipality->toDOMElement($dom));
        if ($this->province) $node->appendChild($this->province->toDOMElement($dom));
        if ($this->phoneTable) $node->appendChild($this->phoneTable->toDOMElement($dom));
        if ($this->issuerEmail) $node->appendChild($this->issuerEmail->toDOMElement($dom));
        if ($this->website) $node->appendChild($this->website->toDOMElement($dom));
        if ($this->economicActivity) $node->appendChild($this->economicActivity->toDOMElement($dom));
        if ($this->vendorCode) $node->appendChild($this->vendorCode->toDOMElement($dom));
        if ($this->internalInvoiceNumber) $node->appendChild($this->internalInvoiceNumber->toDOMElement($dom));
        if ($this->internalOrderNumber) $node->appendChild($this->internalOrderNumber->toDOMElement($dom));
        if ($this->salesZone) $node->appendChild($this->salesZone->toDOMElement($dom));
        if ($this->salesRoute) $node->appendChild($this->salesRoute->toDOMElement($dom));
        if ($this->issuerAdditionalInformation) $node->appendChild($this->issuerAdditionalInformation->toDOMElement($dom));
        if ($this->issueDate) $node->appendChild($this->issueDate->toDOMElement($dom));

        return $node;
    }


    #########################
    ## VALIDATION
    #########################

    public function validate(): bool
    {
        // TODO: implement validation
        return true;
    }


    #########################
    ## GETTERS AND SETTERS ##
    #########################

    public function getIssuerRNC(): ?IssuerRNC
    {
        return $this->issuerRnc;
    }

    public function setIssuerRNC(IssuerRNC $issuerRnc): self
    {
        $this->issuerRnc = $issuerRnc;
        return $this;
    }

    public function getCompanyName(): ?CompanyName
    {
        return $this->companyName;
    }

    public function setCompanyName(CompanyName $companyName): self
    {
        $this->companyName = $companyName;
        return $this;
    }

    public function getLegalName(): ?LegalName
    {
        return $this->legalName;
    }

    public function setLegalName(?LegalName $legalName): self
    {
        $this->legalName = $legalName;
        return $this;
    }

    public function getBranch(): ?Branch
    {
        return $this->branch;
    }

    public function setBranch(?Branch $branch): self
    {
        $this->branch = $branch;
        return $this;
    }

    public function getIssuerAddress(): ?IssuerAddress
    {
        return $this->issuerAddress;
    }

    public function setIssuerAddress(IssuerAddress $issuerAddress): self
    {
        $this->issuerAddress = $issuerAddress;
        return $this;
    }

    public function getMunicipality(): ?Municipality
    {
        return $this->municipality;
    }

    public function setMunicipality(?Municipality $municipality): self
    {
        $this->municipality = $municipality;
        return $this;
    }

    public function getProvince(): ?Province
    {
        return $this->province;
    }

    public function setProvince(?Province $province): self
    {
        $this->province = $province;
        return $this;
    }

    public function getIssuerPhoneTable(): ?IssuerPhoneTable
    {
        return $this->phoneTable;
    }

    public function setIssuerPhoneTable(?IssuerPhoneTable $phoneTable): self
    {
        $this->phoneTable = $phoneTable;
        return $this;
    }

    public function getIssuerEmail(): ?IssuerEmail
    {
        return $this->issuerEmail;
    }

    public function setIssuerEmail(?IssuerEmail $issuerEmail): self
    {
        $this->issuerEmail = $issuerEmail;
        return $this;
    }

    public function getWebsite(): ?Website
    {
        return $this->website;
    }

    public function setWebsite(?Website $website): self
    {
        $this->website = $website;
        return $this;
    }

    public function getEconomicActivity(): ?EconomicActivity
    {
        return $this->economicActivity;
    }

    public function setEconomicActivity(?EconomicActivity $economicActivity): self
    {
        $this->economicActivity = $economicActivity;
        return $this;
    }

    public function getVendorCode(): ?VendorCode
    {
        return $this->vendorCode;
    }

    public function setVendorCode(?VendorCode $vendorCode): self
    {
        $this->vendorCode = $vendorCode;
        return $this;
    }

    public function getInternalInvoiceNumber(): ?InternalInvoiceNumber
    {
        return $this->internalInvoiceNumber;
    }

    public function setInternalInvoiceNumber(?InternalInvoiceNumber $internalInvoiceNumber): self
    {
        $this->internalInvoiceNumber = $internalInvoiceNumber;
        return $this;
    }

    public function getInternalOrderNumber(): ?InternalOrderNumber
    {
        return $this->internalOrderNumber;
    }

    public function setInternalOrderNumber(?InternalOrderNumber $internalOrderNumber): self
    {
        $this->internalOrderNumber = $internalOrderNumber;
        return $this;
    }

    public function getSalesZone(): ?SalesZone
    {
        return $this->salesZone;
    }

    public function setSalesZone(?SalesZone $salesZone): self
    {
        $this->salesZone = $salesZone;
        return $this;
    }

    public function getSalesRoute(): ?SalesRoute
    {
        return $this->salesRoute;
    }

    public function setSalesRoute(?SalesRoute $salesRoute): self
    {
        $this->salesRoute = $salesRoute;
        return $this;
    }

    public function getIssuerAdditionalInformation(): ?IssuerAdditionalInformation
    {
        return $this->issuerAdditionalInformation;
    }

    public function setIssuerAdditionalInformation(?IssuerAdditionalInformation $issuerAdditionalInformation): self
    {
        $this->issuerAdditionalInformation = $issuerAdditionalInformation;
        return $this;
    }

    public function getIssueDate(): ?IssueDate
    {
        return $this->issueDate;
    }

    public function setIssueDate(IssueDate $issueDate): self
    {
        $this->issueDate = $issueDate;
        return $this;
    }
}