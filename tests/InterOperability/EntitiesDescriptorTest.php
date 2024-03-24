<?php

declare(strict_types=1);

namespace SimpleSAML\Test\SAML2;

use DOMElement;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\SAML2\XML\md\EntitiesDescriptor;
use SimpleSAML\XML\DOMDocumentFactory;

/**
 * Class \SimpleSAML\SAML2\EntitiesDescriptorTest
 *
 * @package simplesamlphp\saml2
 */
final class EntitiesDescriptorTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param \DOMElement $metadata;
     */
    #[DataProvider('provideMetadata')]
    public function testUnmarshalling(bool $shouldPass, DOMElement $metadata): void
    {
        try {
            EntitiesDescriptor::fromXML($metadata);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            fwrite(STDERR, $e->getFile() . '(' . strval($e->getLine()) . '):' . $e->getMessage());
            fwrite(STDERR, $e->getTraceAsString());
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array
     */
    public static function provideMetadata(): array
    {
        return [
            'eduGAIN' => [
                true,
                DOMDocumentFactory::fromFile('/tmp/metadata/edugain.xml')->documentElement,
            ],
            'GRNET' => [
                true,
                DOMDocumentFactory::fromFile('/tmp/metadata/grnet.xml')->documentElement,
            ],
        ];
    }
}
