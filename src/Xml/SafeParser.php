<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Xml;

use HarmonyIO\Validation\Exception\InvalidXml;

class SafeParser
{
    /** @var \DOMDocument */
    private $domDocument;

    public function __construct(string $xml)
    {
        $useInternalErrors   = libxml_use_internal_errors(true);
        $disableEntityLoader = libxml_disable_entity_loader(true);

        $domDocument = new \DOMDocument();
        $domDocument->loadXML($xml);

        $libXmlErrors = libxml_get_errors();

        libxml_clear_errors();

        libxml_use_internal_errors($useInternalErrors);
        libxml_disable_entity_loader($disableEntityLoader);

        if ($libXmlErrors) {
            throw new InvalidXml($libXmlErrors[0]->message, $libXmlErrors[0]->code);
        }

        $this->domDocument = $domDocument;
    }

    public function getElementsByTagName(string $tagName): \DOMNodeList
    {
        return $this->domDocument->getElementsByTagName($tagName);
    }
}
