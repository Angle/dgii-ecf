<?php

namespace Angle\ECF;

use DOMDocument;
use DOMElement;
use DOMNode;

interface ECFNodeInterface
{
    public function __construct(array $data);

    public function validate(): bool;

    public function getAttributes(): array;

    public function setChildrenFromDOMNodes(array $children): void;

    public function setChildFromData(string $child, array $data): void;

    public function toDOMElement(DOMDocument $dom): DOMElement;
}