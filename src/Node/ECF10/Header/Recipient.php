<?php

namespace Angle\ECF\Node\ECF10\Header;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;

use Angle\ECF\Node\ECF10\Header\Recipient\RecipientRNC;
use Angle\ECF\Node\ECF10\Header\Recipient\ForeignIdentifier;
use Angle\ECF\Node\ECF10\Header\Recipient\CompanyName;
use Angle\ECF\Node\ECF10\Header\Recipient\Contact;
use Angle\ECF\Node\ECF10\Header\Recipient\RecipientEmail;
use Angle\ECF\Node\ECF10\Header\Recipient\RecipientAddress;
use Angle\ECF\Node\ECF10\Header\Recipient\Municipality;
use Angle\ECF\Node\ECF10\Header\Recipient\Province;
use Angle\ECF\Node\ECF10\Header\Recipient\Country;
use Angle\ECF\Node\ECF10\Header\Recipient\DeliveryDate;
use Angle\ECF\Node\ECF10\Header\Recipient\DeliveryContact;
use Angle\ECF\Node\ECF10\Header\Recipient\DeliveryAddress;
use Angle\ECF\Node\ECF10\Header\Recipient\AdditionalPhone;
use Angle\ECF\Node\ECF10\Header\Recipient\PurchaseOrderDate;
use Angle\ECF\Node\ECF10\Header\Recipient\PurchaseOrderNumber;
use Angle\ECF\Node\ECF10\Header\Recipient\InternalCode;
use Angle\ECF\Node\ECF10\Header\Recipient\PaymentResponsible;
use Angle\ECF\Node\ECF10\Header\Recipient\RecipientAdditionalInformation;

use DateTime;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static Recipient createFromDOMNode(DOMNode $node)
 */
class Recipient extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "Comprador";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'recipientRnc' => [
            'keywords' => ['RNCComprador', 'recipientRnc'],
            'class' => RecipientRNC::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'foreignIdentifier' => [
            'keywords' => ['IdentificadorExtranjero', 'foreignIdentifier'],
            'class' => ForeignIdentifier::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'companyName' => [
            'keywords' => ['RazonSocialComprador', 'companyName'],
            'class' => CompanyName::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'contact' => [
            'keywords' => ['ContactoComprador', 'contact'],
            'class' => Contact::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'recipientEmail' => [
            'keywords' => ['CorreoComprador', 'recipientEmail'],
            'class' => RecipientEmail::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'recipientAddress' => [
            'keywords' => ['DireccionComprador', 'recipientAddress'],
            'class' => RecipientAddress::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'municipality' => [
            'keywords' => ['MunicipioComprador', 'municipality'],
            'class' => Municipality::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'province' => [
            'keywords' => ['ProvinciaComprador', 'province'],
            'class' => Province::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'country' => [
            'keywords' => ['PaisComprador', 'country'],
            'class' => Country::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'deliveryDate' => [
            'keywords' => ['FechaEntrega', 'deliveryDate'],
            'class' => DeliveryDate::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'deliveryContact' => [
            'keywords' => ['ContactoEntrega', 'deliveryContact'],
            'class' => DeliveryContact::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'deliveryAddress' => [
            'keywords' => ['DireccionEntrega', 'deliveryAddress'],
            'class' => DeliveryAddress::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'additionalPhone' => [
            'keywords' => ['TelefonoAdicional', 'additionalPhone'],
            'class' => AdditionalPhone::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'purchaseOrderDate' => [
            'keywords' => ['FechaOrdenCompra', 'purchaseOrderDate'],
            'class' => PurchaseOrderDate::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'purchaseOrderNumber' => [
            'keywords' => ['NumeroOrdenCompra', 'purchaseOrderNumber'],
            'class' => PurchaseOrderNumber::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'internalCode' => [
            'keywords' => ['CodigoInternoComprador', 'internalCode'],
            'class' => InternalCode::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'paymentResponsible' => [
            'keywords' => ['ResponsablePago', 'paymentResponsible'],
            'class' => PaymentResponsible::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
        'recipientAdditionalInformation' => [
            'keywords' => ['InformacionAdicionalComprador', 'recipientAdditionalInformation'],
            'class' => RecipientAdditionalInformation::class,
            'type' => ECFNode::CHILD_UNIQUE,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var RecipientRNC|null */
    protected $recipientRnc;

    /** @var ForeignIdentifier|null */
    protected $foreignIdentifier;

    /** @var CompanyName|null */
    protected $companyName;

    /** @var Contact|null */
    protected $contact;

    /** @var RecipientEmail|null */
    protected $recipientEmail;

    /** @var RecipientAddress|null */
    protected $recipientAddress;

    /** @var Municipality|null */
    protected $municipality;

    /** @var Province|null */
    protected $province;

    /** @var Country|null */
    protected $country;

    /** @var DeliveryDate|null */
    protected $deliveryDate;

    /** @var DeliveryContact|null */
    protected $deliveryContact;

    /** @var DeliveryAddress|null */
    protected $deliveryAddress;

    /** @var AdditionalPhone|null */
    protected $additionalPhone;

    /** @var PurchaseOrderDate|null */
    protected $purchaseOrderDate;

    /** @var PurchaseOrderNumber|null */
    protected $purchaseOrderNumber;

    /** @var InternalCode|null */
    protected $internalCode;

    /** @var PaymentResponsible|null */
    protected $paymentResponsible;

    /** @var RecipientAdditionalInformation|null */
    protected $recipientAdditionalInformation;

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
                case RecipientRNC::NODE_NAME:
                    $this->setRecipientRnc(RecipientRNC::createFromDOMNode($node));
                    break;
                case ForeignIdentifier::NODE_NAME:
                    $this->setForeignIdentifier(ForeignIdentifier::createFromDOMNode($node));
                    break;
                case CompanyName::NODE_NAME:
                    $this->setCompanyName(CompanyName::createFromDOMNode($node));
                    break;
                case Contact::NODE_NAME:
                    $this->setContact(Contact::createFromDOMNode($node));
                    break;
                case RecipientEmail::NODE_NAME:
                    $this->setRecipientEmail(RecipientEmail::createFromDOMNode($node));
                    break;
                case RecipientAddress::NODE_NAME:
                    $this->setRecipientAddress(RecipientAddress::createFromDOMNode($node));
                    break;
                case Municipality::NODE_NAME:
                    $this->setMunicipality(Municipality::createFromDOMNode($node));
                    break;
                case Province::NODE_NAME:
                    $this->setProvince(Province::createFromDOMNode($node));
                    break;
                case Country::NODE_NAME:
                    $this->setCountry(Country::createFromDOMNode($node));
                    break;
                case DeliveryDate::NODE_NAME:
                    $this->setDeliveryDate(DeliveryDate::createFromDOMNode($node));
                    break;
                case DeliveryContact::NODE_NAME:
                    $this->setDeliveryContact(DeliveryContact::createFromDOMNode($node));
                    break;
                case DeliveryAddress::NODE_NAME:
                    $this->setDeliveryAddress(DeliveryAddress::createFromDOMNode($node));
                    break;
                case AdditionalPhone::NODE_NAME:
                    $this->setAdditionalPhone(AdditionalPhone::createFromDOMNode($node));
                    break;
                case PurchaseOrderDate::NODE_NAME:
                    $this->setPurchaseOrderDate(PurchaseOrderDate::createFromDOMNode($node));
                    break;
                case PurchaseOrderNumber::NODE_NAME:
                    $this->setPurchaseOrderNumber(PurchaseOrderNumber::createFromDOMNode($node));
                    break;
                case InternalCode::NODE_NAME:
                    $this->setInternalCode(InternalCode::createFromDOMNode($node));
                    break;
                case PaymentResponsible::NODE_NAME:
                    $this->setPaymentResponsible(PaymentResponsible::createFromDOMNode($node));
                    break;
                case RecipientAdditionalInformation::NODE_NAME:
                    $this->setRecipientAdditionalInformation(RecipientAdditionalInformation::createFromDOMNode($node));
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

        if ($this->recipientRnc) $node->appendChild($this->recipientRnc->toDOMElement($dom));
        if ($this->foreignIdentifier) $node->appendChild($this->foreignIdentifier->toDOMElement($dom));
        if ($this->companyName) $node->appendChild($this->companyName->toDOMElement($dom));
        if ($this->contact) $node->appendChild($this->contact->toDOMElement($dom));
        if ($this->recipientEmail) $node->appendChild($this->recipientEmail->toDOMElement($dom));
        if ($this->recipientAddress) $node->appendChild($this->recipientAddress->toDOMElement($dom));
        if ($this->municipality) $node->appendChild($this->municipality->toDOMElement($dom));
        if ($this->province) $node->appendChild($this->province->toDOMElement($dom));
        if ($this->country) $node->appendChild($this->country->toDOMElement($dom));
        if ($this->deliveryDate) $node->appendChild($this->deliveryDate->toDOMElement($dom));
        if ($this->deliveryContact) $node->appendChild($this->deliveryContact->toDOMElement($dom));
        if ($this->deliveryAddress) $node->appendChild($this->deliveryAddress->toDOMElement($dom));
        if ($this->additionalPhone) $node->appendChild($this->additionalPhone->toDOMElement($dom));
        if ($this->purchaseOrderDate) $node->appendChild($this->purchaseOrderDate->toDOMElement($dom));
        if ($this->purchaseOrderNumber) $node->appendChild($this->purchaseOrderNumber->toDOMElement($dom));
        if ($this->internalCode) $node->appendChild($this->internalCode->toDOMElement($dom));
        if ($this->paymentResponsible) $node->appendChild($this->paymentResponsible->toDOMElement($dom));
        if ($this->recipientAdditionalInformation) $node->appendChild($this->recipientAdditionalInformation->toDOMElement($dom));

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

    public function getRecipientRnc(): ?RecipientRNC
    {
        return $this->recipientRnc;
    }

    public function setRecipientRnc(?RecipientRNC $recipientRnc): self
    {
        $this->recipientRnc = $recipientRnc;
        return $this;
    }

    public function getForeignIdentifier(): ?ForeignIdentifier
    {
        return $this->foreignIdentifier;
    }

    public function setForeignIdentifier(?ForeignIdentifier $foreignIdentifier): self
    {
        $this->foreignIdentifier = $foreignIdentifier;
        return $this;
    }

    public function getCompanyName(): ?CompanyName
    {
        return $this->companyName;
    }

    public function setCompanyName(?CompanyName $companyName): self
    {
        $this->companyName = $companyName;
        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): self
    {
        $this->contact = $contact;
        return $this;
    }

    public function getRecipientEmail(): ?RecipientEmail
    {
        return $this->recipientEmail;
    }

    public function setRecipientEmail(?RecipientEmail $recipientEmail): self
    {
        $this->recipientEmail = $recipientEmail;
        return $this;
    }

    public function getRecipientAddress(): ?RecipientAddress
    {
        return $this->recipientAddress;
    }

    public function setRecipientAddress(?RecipientAddress $recipientAddress): self
    {
        $this->recipientAddress = $recipientAddress;
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

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function getDeliveryDate(): ?DeliveryDate
    {
        return $this->deliveryDate;
    }

    public function setDeliveryDate(?DeliveryDate $deliveryDate): self
    {
        $this->deliveryDate = $deliveryDate;
        return $this;
    }

    public function getDeliveryContact(): ?DeliveryContact
    {
        return $this->deliveryContact;
    }

    public function setDeliveryContact(?DeliveryContact $deliveryContact): self
    {
        $this->deliveryContact = $deliveryContact;
        return $this;
    }

    public function getDeliveryAddress(): ?DeliveryAddress
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(?DeliveryAddress $deliveryAddress): self
    {
        $this->deliveryAddress = $deliveryAddress;
        return $this;
    }

    public function getAdditionalPhone(): ?AdditionalPhone
    {
        return $this->additionalPhone;
    }

    public function setAdditionalPhone(?AdditionalPhone $additionalPhone): self
    {
        $this->additionalPhone = $additionalPhone;
        return $this;
    }

    public function getPurchaseOrderDate(): ?PurchaseOrderDate
    {
        return $this->purchaseOrderDate;
    }

    public function setPurchaseOrderDate(?PurchaseOrderDate $purchaseOrderDate): self
    {
        $this->purchaseOrderDate = $purchaseOrderDate;
        return $this;
    }

    public function getPurchaseOrderNumber(): ?PurchaseOrderNumber
    {
        return $this->purchaseOrderNumber;
    }

    public function setPurchaseOrderNumber(?PurchaseOrderNumber $purchaseOrderNumber): self
    {
        $this->purchaseOrderNumber = $purchaseOrderNumber;
        return $this;
    }

    public function getInternalCode(): ?InternalCode
    {
        return $this->internalCode;
    }

    public function setInternalCode(?InternalCode $internalCode): self
    {
        $this->internalCode = $internalCode;
        return $this;
    }

    public function getPaymentResponsible(): ?PaymentResponsible
    {
        return $this->paymentResponsible;
    }

    public function setPaymentResponsible(?PaymentResponsible $paymentResponsible): self
    {
        $this->paymentResponsible = $paymentResponsible;
        return $this;
    }

    public function getRecipientAdditionalInformation(): ?RecipientAdditionalInformation
    {
        return $this->recipientAdditionalInformation;
    }

    public function setRecipientAdditionalInformation(?RecipientAdditionalInformation $recipientAdditionalInformation): self
    {
        $this->recipientAdditionalInformation = $recipientAdditionalInformation;
        return $this;
    }
}