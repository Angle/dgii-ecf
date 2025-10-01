<?php

namespace Angle\ECF\Tests;

use Angle\ECF\Node\ECF10\ECF10;
use Angle\ECF\Service\SignatureGenerator;
use DateTime;
use DOMDocument;
use DOMXPath;
use PHPUnit\Framework\TestCase;



final class SignatureTest extends TestCase
{
    public function test(): void
    {
        $signatureGenerator = new SignatureGenerator();

        $files = glob(__DIR__ . '/../test-data/*.xml', GLOB_ERR);
        $pfxFile = __DIR__ . '/../test-data/test_certificate.pfx';
        $pfxPassword = __DIR__ . '/../test-data/pfx_password.txt';

        $i = 0;
        foreach ($files as $f) {
            $i++;

            try {
                $signedXml =  $signatureGenerator->signXml($pfxFile, file_get_contents($pfxPassword), file_get_contents($f));
                $dom = new DOMDocument();
                $dom->loadXml($signedXml);

                $xpath = new DOMXPath($dom);
                $xpath->registerNamespace('ds', 'http://www.w3.org/2000/09/xmldsig#');
                $nodes = $xpath->query('//ds:SignatureValue');
                if ($nodes->length > 0) {
                    // Access the first (and only) node in the list
                    $singleNode = $nodes->item(0);

                    // Get the string value from that node
                    $nodeValue = $singleNode->nodeValue;

                    // Output the value
                    echo "The value of the node is: " . $nodeValue;
                }
            } catch (\Exception $e) {
                $error = $e->getMessage();

                // Signing failed!
                echo "-> Signing FAILED" . PHP_EOL;

                echo "Error:" . PHP_EOL;
                print_r($error);
                echo PHP_EOL;

                $this->fail('XML could not be signed');
            }

            $this->assertIsString($signedXml);
            $this->assertNotEmpty($signedXml);
        }

    }
}