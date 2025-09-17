<?php

namespace Angle\ECF\Node\ECF10;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\Node\ECF10\Header\DocId;
use Angle\ECF\Node\ECF10\Header\Issuer;
use Angle\ECF\Node\ECF10\Header\OtherCurrency;
use Angle\ECF\Node\ECF10\Header\Recipient;
use Angle\ECF\Node\ECF10\Header\Totals;
use Angle\ECF\Node\ECF10\Header\Version;
use DateTime;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static Header createFromDOMNode(DOMNode $node)
 */
class Header extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "Encabezado";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'version' => [
            'keywords'  => ['Version', 'version'],
            'class'     => Version::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'docId' => [
            'keywords'  => ['IdDoc', 'docId'],
            'class'     => DocId::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'issuer' => [
            'keywords'  => ['Emisor', 'issuer'],
            'class'     => Issuer::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'recipient' => [
            'keywords'  => ['Comprador', 'recipient'],
            'class'     => Recipient::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        // 'additionalInformation' => [
        //     'keywords'  => ['InformacionesAdicionales', 'additionalInformation'],
        //     'class'     => AdditionalInformation::class,
        //     'type'      => ECFNode::CHILD_UNIQUE,
        // ],
        // 'transport' => [
        //     'keywords'  => ['Transporte', 'transport'],
        //     'class'     => Transport::class,
        //     'type'      => ECFNode::CHILD_UNIQUE,
        // ],
        'totales' => [
            'keywords'  => ['Totales', 'totals'],
            'class'     => Totals::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'otherCurrency' => [
            'keywords'  => ['OtraMoneda', 'otherCurrency'],
            'class'     => OtherCurrency::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
    ];



    #########################
    ##      PROPERTIES     ##
    #########################

    /**
     * @var Version
     */
    protected $version;

    /**
     * @var DocId
     */
    protected $docId;

    /**
     * @var Issuer
     */
    protected $issuer;

    /**
     * @var Recipient
     */
    protected $recipient;

    /**
     * @var Totals
     */
    protected $totals;

    /**
     * @var OtherCurrency|null
     */
    protected $otherCurrency;


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
                // TODO: we are skipping the actual text inside the Node.. is this useful?
                // TODO: DOMText
                continue;
            }

            switch ($node->localName) {
                case Version::NODE_NAME:
                    $version = Version::createFromDOMNode($node);
                    $this->setVersion($version);
                    break;
                case DocId::NODE_NAME:
                    $docId = DocId::createFromDOMNode($node);
                    $this->setDocId($docId);
                    break;
                case Issuer::NODE_NAME:
                    $issuer = Issuer::createFromDOMNode($node);
                    $this->setIssuer($issuer);
                    break;
                case Recipient::NODE_NAME:
                    $recipient = Recipient::createFromDOMNode($node);
                    $this->setRecipient($recipient);
                    break;
                case Totals::NODE_NAME:
                    $totals = Totals::createFromDOMNode($node);
                    $this->setTotals($totals);
                    break;
                case OtherCurrency::NODE_NAME:
                    $otherCurrency = OtherCurrency::createFromDOMNode($node);
                    $this->setOtherCurrency($otherCurrency);
                    break;
                default:
                    //throw new ECFException(sprintf("Unknown children node '%s' in %s", $node->nodeName, self::NODE_NS_NAME));
            }
        }
    }


    #########################
    ## CFDI NODE TO DOM TRANSLATION
    #########################

    public function toDOMElement(DOMDocument $dom): DOMElement
    {
        $node = $dom->createElement(self::NODE_NAME);

        foreach ($this->getAttributes() as $attr => $value) {
            $node->setAttribute($attr, $value);
        }

        // Header Node
        if ($this->version) {
            // TODO: What happens if the version is not set?
            $versionNode = $this->version->toDOMElement($dom);
            $node->appendChild($versionNode);
        }

        // docId Node
        if ($this->docId) {
            // TODO: What happens if the docId is not set?
            $docIdNode = $this->docId->toDOMElement($dom);
            $node->appendChild($docIdNode);
        }

        // issuer Node
        if ($this->issuer) {
            // TODO: What happens if the issuer is not set?
            $issuerNode = $this->issuer->toDOMElement($dom);
            $node->appendChild($issuerNode);
        }

        // recipient Node
        if ($this->recipient) {
            // TODO: What happens if the recipient is not set?
            $recipientNode = $this->recipient->toDOMElement($dom);
            $node->appendChild($recipientNode);
        }

        // totals Node
        if ($this->totals) {
            // TODO: What happens if the totals is not set?
            $totalsNode = $this->totals->toDOMElement($dom);
            $node->appendChild($totalsNode);
        }

        // otherCurrency Node
        if ($this->otherCurrency) {
            // TODO: What happens if the otherCurrency is not set?
            $otherCurrencyNode = $this->otherCurrency->toDOMElement($dom);
            $node->appendChild($otherCurrencyNode);
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

    /**
     * @return Version
     */
    public function getVersion(): ?Version
    {
        return $this->version;
    }

    /**
     * @param Version $version
     * @return Header
     */
    public function setVersion(Version $version): self
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return DocId
     */
    public function getDocId(): ?DocId
    {
        return $this->docId;
    }

    /**
     * @param DocId $docId
     * @return Header
     */
    public function setDocId(DocId $docId): self
    {
        $this->docId = $docId;
        return $this;
    }

    /**
     * @return Issuer
     */
    public function getIssuer(): ?Issuer
    {
        return $this->issuer;
    }

    /**
     * @param Issuer $issuer
     * @return Header
     */
    public function setIssuer(Issuer $issuer): self
    {
        $this->issuer = $issuer;
        return $this;
    }

    /**
     * @return Recipient
     */
    public function getRecipient(): ?Recipient
    {
        return $this->recipient;
    }

    /**
     * @param Recipient|null $recipient
     * @return Header
     */
    public function setRecipient(?Recipient $recipient): self
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @return Totals
     */
    public function getPagination(): ?Totals
    {
        return $this->totals;
    }

    /**
     * @param Totals $totals
     * @return Header
     */
    public function setTotals(?Totals $totals): self
    {
        $this->totals = $totals;
        return $this;
    }

    /**
     * @return OtherCurrency|null
     */
    public function getOtherCurrency(): ?OtherCurrency
    {
        return $this->otherCurrency;
    }

    /**
     * @param OtherCurrency|null $otherCurrency
     * @return Header
     */
    public function setOtherCurrency(?OtherCurrency $otherCurrency): self
    {
        $this->otherCurrency = $otherCurrency;
        return $this;
    }
}