<?php

namespace Angle\ECF\Tests;

use Angle\ECF\Node\ECF10\ECF10;
use Angle\ECF\Service\PDF;
use DateTime;
use DOMDocument;
use PHPUnit\Framework\TestCase;

use Twig\Environment as Twig;
use Twig\Loader\FilesystemLoader;


final class PdfTest extends TestCase
{
    public function test(): void
    {
        $pfxFile = __DIR__ . '/../test-data/test_certificate.pfx';
        $pfxPassword = __DIR__ . '/../test-data/pfx_password.txt';

        $twig = new Twig(new FilesystemLoader(realpath(__DIR__ . '/../templates')));

        $pdfService = new PDF($twig);

        $files = glob(__DIR__ . '/../test-data/*.xml', GLOB_ERR);
        $logoFilePath =  realpath(__DIR__ . '/../test-data/logo.png');

        $i = 0;
        foreach ($files as $f) {
            $i++;
            try {
                $dom = new DOMDocument();
                $dom->load($f);
                $ecfNode = $dom->firstChild;
                $ecf = ECF10::createFromDOMNode($ecfNode);
                if(!$ecf) {
                    $this->fail('Failed to create ecf from data');
                    return;
                }
                $ecf->generateAdditionalTaxRates();
                $ecf->calculateTotals();

                //if xml is not signed lets sign it (we need for qr code)
                if(!$ecf->getSignature()) {
                    $ecf = $ecf->sign($pfxFile, file_get_contents($pfxPassword));
                }

                print_r($ecf->getSignatureTimestamp()->getValue());

                if(!$ecf) {
                    $this->fail('Failed to sign ecf');
                    return;
                }

            } catch (\Exception $e) {
                $ecf = null;
                $error = $e->getMessage();
            }

            if (!$ecf) {
                // Loading failed!
                echo "-> Parse FAILED" . PHP_EOL;

                echo "Error:" . PHP_EOL;
                print_r($error);
                echo PHP_EOL;

                $this->fail('ECF could not be parsed from the XML file');
            }

            $pdfContent = $pdfService->build($ecf, $logoFilePath);

            $this->assertIsString($pdfContent);
            $this->assertNotEmpty($pdfContent);

            $outputFilePath = __DIR__ . '/../test-data/test_output_' .  (new DateTime())->format('dmYHis') . $i . '.pdf';
            file_put_contents($outputFilePath, $pdfContent);

            $this->assertFileExists($outputFilePath);
            $this->assertGreaterThan(0, filesize($outputFilePath));
        }

    }
}