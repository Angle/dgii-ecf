<?php

namespace Angle\ECF;

use DateTime;
use DOMDocument;
use DOMNode;


interface ECFInterface
{
    // XML Functions
    public static function createFromDOMNode(DOMNode $node);

    public function getOriginalXml(): ?string;

    public function setOriginalXml(?string $xmlString);

    public function toDOMDocument(): DOMDocument;

    public function toXML();
}