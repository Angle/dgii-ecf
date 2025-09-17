<?php

namespace Angle\ECF\Node\ECF10;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\ECFInterface;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static ECF10 createFromDOMNode(DOMNode $node)
 */
class ECF10 extends ECFNode implements ECFInterface
{
    #########################
    ##       CATALOG       ##
    #########################


    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = 'ECF';

    protected static $baseAttributes = [];


    ################################
    ## PROPERTY NAME TRANSLATIONS ##
    ################################

    protected static $attributes = [];

    protected static $children = [
        'header' => [
            'keywords'  => ['Encabezado', 'header'],
            'class'     => Header::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'itemDetails' => [
            'keywords'  => ['DetallesItems', 'itemDetails'],
            'class'     => ItemDetails::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'subtotals' => [
            'keywords'  => ['Subtotales', 'subtotals'],
            'class'     => Subtotals::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'discountsOrSurcharges' => [
            'keywords'  => ['DescuentosORecargos', 'descuentosORecargos'],
            'class'     => DiscountsOrSurcharges::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'pagination' => [
            'keywords'  => ['Paginacion', 'pagination'],
            'class'     => Pagination::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'referenceInformation' => [
            'keywords'  => ['InformacionReferencia', 'referenceInformation'],
            'class'     => ReferenceInformation::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'signatureTimestamp' => [
            'keywords'  => ['FechaHoraFirma', 'signatureTimestamp'],
            'class'     => SignatureTimestamp::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        //TODO: Signature
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /**
     * @var Header
     */
    protected $header;

    /**
     * @var ItemDetails
     */
    protected $itemDetails;

    /**
     * @var Subtotals|null
     */
    protected $subtotals;

    /**
     * @var DiscountsOrSurcharges|null
     */
    protected $discountsOrSurcharges;

    /**
     * @var Pagination|null
     */
    protected $pagination;

    /**
     * @var ReferenceInformation|null
     */
    protected $referenceInformation;

    /**
     * @var SignatureTimestamp
     */
    protected $signatureTimestamp;

    // TODO: Signature


    /**
     * @var string|null
     */
    protected $originalXml = null;


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
                // TODO: we are skipping the actual text inside the Node.. is this useful?
                // TODO: DOMText
                continue;
            }

            switch ($node->localName) {
                case Header::NODE_NAME:
                    $header = Header::createFromDOMNode($node);
                    $this->setHeader($header);
                    break;
                case ItemDetails::NODE_NAME:
                    $issuer = ItemDetails::createFromDOMNode($node);
                    $this->setItemDetails($issuer);
                    break;
                case Subtotals::NODE_NAME:
                    $subtotals = Subtotals::createFromDOMNode($node);
                    $this->setSubtotals($subtotals);
                    break;
                case DiscountsOrSurcharges::NODE_NAME:
                    $discountsOrSurcharges = DiscountsOrSurcharges::createFromDOMNode($node);
                    $this->setDiscountsOrSurcharges($discountsOrSurcharges);
                    break;
                case Pagination::NODE_NAME:
                    $pagination = Pagination::createFromDOMNode($node);
                    $this->setPagination($pagination);
                    break;
                case ReferenceInformation::NODE_NAME:
                    $referenceInformation = ReferenceInformation::createFromDOMNode($node);
                    $this->setReferenceInformation($referenceInformation);
                    break;
                case SignatureTimestamp::NODE_NAME:
                    $signatureTimestamp = SignatureTimestamp::createFromDOMNode($node);
                    $this->setSignatureTimestamp($signatureTimestamp);
                    break;
                //TODO: Signature
                default:
                    //throw new ECFException(sprintf("Unknown children node '%s' in %s", $node->nodeName, self::NODE_NS_NAME));
            }
        }
    }


    #########################
    ## CFDI TO DOM TRANSLATION
    #########################

    public function toDOMElement(DOMDocument $dom): DOMElement
    {
        $node = $dom->createElement(self::NODE_NAME);

        foreach ($this->getAttributes() as $attr => $value) {
            $node->setAttribute($attr, $value);
        }

        // Header Node
        if ($this->header) {
            // TODO: What happens if the header is not set?
            $headerNode = $this->header->toDOMElement($dom);
            $node->appendChild($headerNode);
        }

        // ItemDetails Node
        if ($this->itemDetails) {
            // TODO: What happens if the itemDetails is not set?
            $itemDetailsNode = $this->itemDetails->toDOMElement($dom);
            $node->appendChild($itemDetailsNode);
        }

        // Subtotals Node
        if ($this->subtotals) {
            // TODO: What happens if the subtotals is not set?
            $subtotalsNode = $this->subtotals->toDOMElement($dom);
            $node->appendChild($subtotalsNode);
        }

        // DiscountsOrSurcharges Node
        if ($this->discountsOrSurcharges) {
            // TODO: What happens if the discountsOrSurcharges is not set?
            $discountsOrSurchargesNode = $this->discountsOrSurcharges->toDOMElement($dom);
            $node->appendChild($discountsOrSurchargesNode);
        }

        // Pagination Node
        if ($this->pagination) {
            // TODO: What happens if the pagination is not set?
            $paginationNode = $this->pagination->toDOMElement($dom);
            $node->appendChild($paginationNode);
        }

        // ReferenceInformation Node
        if ($this->referenceInformation) {
            // TODO: What happens if the referenceInformation is not set?
            $referenceInformationNode = $this->referenceInformation->toDOMElement($dom);
            $node->appendChild($referenceInformationNode);
        }

        // SignatureTimestamp Node
        if ($this->signatureTimestamp) {
            // TODO: What happens if the signatureTimestamp is not set?
            $signatureTimestampNode = $this->signatureTimestamp->toDOMElement($dom);
            $node->appendChild($signatureTimestampNode);
        }

        //TODO: Signature

        return $node;
    }


    #########################
    ##      CFDI TO XML    ##
    #########################

    public function toDOMDocument(): DOMDocument
    {
        $dom = new \DOMDocument('1.0','UTF-8');
        $dom->preserveWhiteSpace = false;

        $cfdiNode = $this->toDOMElement($dom);
        $dom->appendChild($cfdiNode);

        return $dom;
    }

    // TODO: DOMDocument duplicates the Namespace declarations of any child
    public function toXML()
    {
        return $this->toDOMDocument()->saveXML();
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
    ##   SPECIAL METHODS   ##
    #########################

    /**
     * @return string|null
     */
    public function getOriginalXml(): ?string
    {
        return $this->originalXml;
    }

    /**
     * @param string|null $originalXml
     * @return self
     */
    public function setOriginalXml(?string $originalXml)
    {
        $this->originalXml = $originalXml;
        return $this;
    }

    #########################
    ##  INTERFACE METHODS  ##
    #########################




    #########################
    ## GETTERS AND SETTERS ##
    #########################



    #########################
    ##       CHILDREN      ##
    #########################

    /**
     * @return Header
     */
    public function getHeader(): ?Header
    {
        return $this->header;
    }

    /**
     * @param Header $header
     * @return ECF10
     */
    public function setHeader(Header $header): self
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @return ItemDetails
     */
    public function getItemDetails(): ?ItemDetails
    {
        return $this->itemDetails;
    }

    /**
     * @param ItemDetails $itemDetails
     * @return ECF10
     */
    public function setItemDetails(ItemDetails $itemDetails): self
    {
        $this->itemDetails = $itemDetails;
        return $this;
    }

    /**
     * @return Subtotals
     */
    public function getSubtotals(): ?Subtotals
    {
        return $this->subtotals;
    }

    /**
     * @param Subtotals $subtotals
     * @return ECF10
     */
    public function setSubtotals(Subtotals $subtotals): self
    {
        $this->subtotals = $subtotals;
        return $this;
    }

    /**
     * @return DiscountsOrSurcharges
     */
    public function getDiscountsOrSurcharges(): ?DiscountsOrSurcharges
    {
        return $this->discountsOrSurcharges;
    }

    /**
     * @param DiscountsOrSurcharges|null $discountsOrSurcharges
     * @return ECF10
     */
    public function setDiscountsOrSurcharges(?DiscountsOrSurcharges $discountsOrSurcharges): self
    {
        $this->discountsOrSurcharges = $discountsOrSurcharges;
        return $this;
    }

    /**
     * @return Pagination|null
     */
    public function getPagination(): ?Pagination
    {
        return $this->pagination;
    }

    /**
     * @param Pagination|null $pagination
     * @return ECF10
     */
    public function setPagination(?Pagination $pagination): self
    {
        $this->pagination = $pagination;
        return $this;
    }

    /**
     * @return ReferenceInformation|null
     */
    public function getReferenceInformation(): ?ReferenceInformation
    {
        return $this->referenceInformation;
    }

    /**
     * @param ReferenceInformation|null $referenceInformation
     * @return ECF10
     */
    public function setReferenceInformation(?ReferenceInformation $referenceInformation): self
    {
        $this->referenceInformation = $referenceInformation;
        return $this;
    }

    /**
     * @return SignatureTimestamp|null
     */
    public function getSignatureTimestamp(): ?SignatureTimestamp
    {
        return $this->signatureTimestamp;
    }

    /**
     * @param SignatureTimestamp|null $signatureTimestamp
     * @return ECF10
     */
    public function setSignatureTimestamp(?SignatureTimestamp $signatureTimestamp): self
    {
        $this->signatureTimestamp = $signatureTimestamp;
        return $this;
    }


    #########################
    ##      LIBRARY        ##
    #########################

    // none.


    #########################
    ##       HELPER        ##
    #########################

    /**
     * Clean a string whitespace according to the CFDI Spec, used for generating an original chain sequence
     * @param string $s
     * @return string
     */
    public static function cleanWhitespace(string $s): string
    {
        // Replace all non visible characters with a single space
        $s = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $s);

        // Trim whitespace at the beginning and end of the string
        $s = trim($s);

        // Collapse multiple spaces into a single space
        $s = preg_replace('/\s+/u', ' ', $s);

        return $s;
    }
}