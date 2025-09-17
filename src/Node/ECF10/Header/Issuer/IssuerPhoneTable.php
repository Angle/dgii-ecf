<?php

namespace Angle\ECF\Node\ECF10\Header\Issuer;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;
use Angle\ECF\Node\ECF10\Header\Issuer\PhoneTable\IssuerPhone;
use DateTime;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static IssuerPhoneTable createFromDOMNode(DOMNode $node)
 */
class IssuerPhoneTable extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "TablaTelefonoEmisor";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'issuerPhone' => [
            'keywords'  => ['TelefonoEmisor', 'issuerPhone'],
            'class'     => IssuerPhone::class,
            'type'      => ECFNode::CHILD_ARRAY,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var IssuerPhone[] issuerPhones */
    protected $issuerPhones = [];

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
                case IssuerPhone::NODE_NAME:
                    $issuerPhone = IssuerPhone::createFromDomNode($node);
                    $this->addIssuerPhone($issuerPhone);
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
        foreach ($this->issuerPhones as $issuerPhone) {
            $issuerPhoneNode = $issuerPhone->toDOMElement($dom);
            $node->appendChild($issuerPhoneNode);
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
     * @return IssuerPhone[]
     */
    public function getIssuerPhones(): ?array
    {
        return $this->issuerPhones;
    }

    /**
     * @param IssuerPhone[] $issuerPhones
     * @return IssuerPhoneTable
     */
    public function setIssuerPhones(array $issuerPhones): self
    {
        $this->issuerPhones = $issuerPhones;
        return $this;
    }

    /**
     * @param IssuerPhone $issuerPhone
     * @return IssuerPhoneTable
     */
    public function addIssuerPhone(IssuerPhone $issuerPhone): self
    {
        $this->issuerPhones[] = $issuerPhone;
        return $this;
    }
}