<?php

namespace Angle\ECF\Tests;

use DOMDocument;
use PHPUnit\Framework\TestCase;

use Angle\ECF\ECFInterface;
use Angle\ECF\Node\ECF10\ECF10;


final class ParserTest extends TestCase
{
    public function testValidate(): void
    {
        $files = glob(__DIR__ . '/../test-data/1.xml', GLOB_ERR);

        $realFiles = [];
        foreach ($files as $filename) {
            $realFiles[] = realpath($filename);
        }

        print_r($realFiles);


        foreach ($realFiles as $filename) {
            echo "#################################################################" . PHP_EOL;
            echo "## Source XML: " . $filename . PHP_EOL;
            echo "#################################################################" . PHP_EOL . PHP_EOL;


            echo "## Original XML ## " . PHP_EOL . PHP_EOL;
            echo file_get_contents($filename);
            echo PHP_EOL . PHP_EOL;

            $error = null;
            try {
                $dom = new DOMDocument();
                $dom->load($filename);
                $ecfNode = $dom->firstChild;
                $ecf = ECF10::createFromDOMNode($ecfNode);
                $ecf->generateAdditionalTaxRates();
                $ecf->calculateTotals();
                $ecf->createPagination(10);
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

                // echo "Validations:" . PHP_EOL;
                // echo PHP_EOL;

                $this->fail('ECF could not be parsed from the XML file');
            }

            $this->assertInstanceOf(ECFInterface::class, $ecf);

            // Loading success!
            echo "-> Parse SUCCESS!!" . PHP_EOL;

            echo "Error:" . PHP_EOL;
            print_r($error);
            echo PHP_EOL;

            // echo "Validations:" . PHP_EOL;
            echo PHP_EOL;

            echo "## Parsed ECF Object ## " . PHP_EOL . PHP_EOL;
            print_r($ecf);
            echo PHP_EOL . PHP_EOL;

            echo "## Output XML (reproduced) ## " . PHP_EOL . PHP_EOL;
            echo $ecf->toXML();
            echo PHP_EOL . PHP_EOL;

        }
    }
}