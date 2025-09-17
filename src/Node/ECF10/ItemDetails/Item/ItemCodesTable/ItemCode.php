<?php

namespace Angle\ECF\Node\ECF10\ItemDetails\Item\ItemCodesTable;

use Angle\ECF\ECFNode;
use Angle\ECF\ECFException;

use Angle\ECF\Node\ECF10\ItemDetails\Item\ItemCodesTable\ItemCode\CodeType;
use Angle\ECF\Node\ECF10\ItemDetails\Item\ItemCodesTable\ItemCode\CodeValue;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * @method static ItemCode createFromDOMNode(DOMNode $node)
 */
class ItemCode extends ECFNode
{
    #########################
    ##        PRESETS      ##
    #########################

    const NODE_NAME = "CodigosItem";

    protected static $baseAttributes = [];


    #########################
    ## PROPERTY NAME TRANSLATIONS ##
    #########################

    protected static $attributes = [];

    protected static $children = [
        'codeType' => [
            'keywords'  => ['TipoCodigo', 'codeType'],
            'class'     => CodeType::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
        'codeValue' => [
            'keywords'  => ['CodigoItem', 'codeValue'],
            'class'     => CodeValue::class,
            'type'      => ECFNode::CHILD_UNIQUE,
        ],
    ];


    #########################
    ##      PROPERTIES     ##
    #########################

    /** @var CodeType */
    protected $codeType;

    /** @var CodeValue */
    protected $codeValue;


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
                case CodeType::NODE_NAME:
                    $this->setCodeType(CodeType::createFromDOMNode($node));
                    break;
                case CodeValue::NODE_NAME:
                    $this->setCodeValue(CodeValue::createFromDOMNode($node));
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

        if ($this->codeType) {
            $node->appendChild($this->codeType->toDOMElement($dom));
        }

        if ($this->codeValue) {
            $node->appendChild($this->codeValue->toDOMElement($dom));
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

    public function getCodeType(): ?CodeType
    {
        return $this->codeType;
    }

    public function setCodeType(CodeType $codeType): self
    {
        $this->codeType = $codeType;
        return $this;
    }

    public function getCodeValue(): ?CodeValue
    {
        return $this->codeValue;
    }

    public function setCodeValue(CodeValue $codeValue): self
    {
        $this->codeValue = $codeValue;
        return $this;
    }
}