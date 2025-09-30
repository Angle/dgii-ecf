<?php

namespace Angle\ECF\Tests;

use Angle\ECF\Service\SignatureGenerator;
use DateTime;
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
            $outputFilePath = __DIR__ . '/../test-data/signed_xml_output_' .  (new DateTime())->format('dmYHis') . $i . '.pdf';
            // print_r($pfxPassword);
            // print_r(file_get_contents($pfxPassword));
            try {
                $signedXml =  $signatureGenerator->signXml($pfxFile, file_get_contents($pfxPassword), $f);
            } catch (\Exception $e) {
                $ecf = null;
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