<?php

namespace Saleh7\Zatca\Tests;

use PHPUnit\Framework\TestCase;
use Saleh7\Zatca\Helpers\InvoiceExtension;

class InvoiceExtensionTest extends TestCase
{
    /**
     * Test that fromString() creates an InvoiceExtension instance.
     */
    public function test_from_string_creates_instance(): void
    {
        $xmlString = '<root><child>Content</child></root>';
        $extension = InvoiceExtension::fromString($xmlString);

        $this->assertInstanceOf(InvoiceExtension::class, $extension);
        $this->assertStringContainsString('Content', $extension->toXml());
    }

    /**
     * Test that newInstance() creates an element with the right value and attributes.
     */
    public function test_new_instance_creates_element(): void
    {
        $extension = InvoiceExtension::newInstance('test:Element', 'Hello', ['attr' => 'value']);
        $xml = $extension->toXml();

        $this->assertStringContainsString('Hello', $xml);
        $this->assertStringContainsString('attr="value"', $xml);
    }

    /**
     * Test find() and findAll() methods.
     */
    public function test_find_and_find_all(): void
    {
        $xmlString = '<root><child>One</child><child>Two</child></root>';
        $extension = InvoiceExtension::fromString($xmlString);

        $found = $extension->find('child');
        $this->assertNotNull($found, 'find() should return a node');

        $all = $extension->findAll('child');
        $this->assertCount(2, $all, 'There should be 2 child nodes');
    }

    /**
     * Test that remove() removes a child node.
     */
    public function test_remove(): void
    {
        $xmlString = '<root><child>RemoveMe</child></root>';
        $extension = InvoiceExtension::fromString($xmlString);

        $child = $extension->find('child');
        $this->assertNotNull($child, 'Child node should exist');
        $child->remove();

        $this->assertEmpty($extension->findAll('child'), 'Child node should be removed');
    }

    /**
     * Test that toXml() returns a valid XML string.
     */
    public function test_to_xml(): void
    {
        $xmlString = '<root><child>Text</child></root>';
        $extension = InvoiceExtension::fromString($xmlString);
        $xmlOutput = $extension->toXml();

        $this->assertStringStartsWith('<?xml', $xmlOutput, 'XML declaration should be present');
        $this->assertStringContainsString('<child>Text</child>', $xmlOutput);
    }

    /**
     * Test that computeXmlDigest() returns a valid Base64-encoded SHA-256 digest.
     *
     * The XML includes dummy nodes with proper namespace declarations so that XPath queries work.
     */
    public function test_compute_xml_digest(): void
    {
        $xmlString = <<<'XML'
<root xmlns:ext="urn:oasis:names:specification:ubl:dsig:enveloped:xades" 
      xmlns:cac="urn:oasis:names:specification:ubl:cac" 
      xmlns:cbc="urn:oasis:names:specification:ubl:cbc">
    <child>DigestTest</child>
    <ext:UBLExtensions>
        <dummy>Remove this</dummy>
    </ext:UBLExtensions>
    <cac:Signature>Remove this too</cac:Signature>
    <cac:AdditionalDocumentReference>
        <cbc:ID>QR</cbc:ID>
    </cac:AdditionalDocumentReference>
</root>
XML;

        $extension = InvoiceExtension::fromString($xmlString);
        $digest = $extension->computeXmlDigest();

        $this->assertNotEmpty($digest, 'Digest should not be empty');
        $decoded = base64_decode($digest, true);
        $this->assertEquals(32, strlen($decoded), 'SHA-256 digest should be 32 bytes');
    }
}
