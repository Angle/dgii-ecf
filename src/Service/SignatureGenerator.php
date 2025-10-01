<?php

namespace Angle\ECF\Service;
require 'vendor/autoload.php';
use DOMDocument;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;


class SignatureGenerator
{
    public function signXml($pfxFile, $pfxPassword, $xmlContent) : string|bool
    {
        $pfxContent = file_get_contents($pfxFile);

        $certs = [];

        if (!openssl_pkcs12_read($pfxContent, $certs, $pfxPassword)) {
            //Error: Unable to read the certificate file. Check the path and password
            return false;
        }

        $privateKey = $certs['pkey'];
        $publicKeyCert = $certs['cert'];

        $doc = new DOMDocument();

        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        $doc->loadXML($xmlContent);

        $objDSig = new XMLSecurityDSig();

        $objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);
        $objDSig->addReference($doc, XMLSecurityDSig::SHA256,['http://www.w3.org/2000/09/xmldsig#enveloped-signature']);

        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, ['type' => 'private']);
        $objKey->loadKey($privateKey);

        $objDSig->sign($objKey);
        $objDSig->add509Cert($publicKeyCert);

        $objDSig->appendSignature($doc->documentElement);

        return $doc->saveXml();
    }
}