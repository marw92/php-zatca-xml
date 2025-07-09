<?php

namespace Saleh7\Zatca;

use InvalidArgumentException;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

/**
 * Class ExtensionContent
 *
 * Represents extension content containing UBL document signatures.
 */
class ExtensionContent implements XmlSerializable
{
    /** @var UBLDocumentSignatures|null The UBL document signatures. */
    private ?UBLDocumentSignatures $UBLDocumentSignatures = null;

    /**
     * Set the UBL document signatures.
     */
    public function setUBLDocumentSignatures(UBLDocumentSignatures $UBLDocumentSignatures): self
    {
        $this->UBLDocumentSignatures = $UBLDocumentSignatures;

        return $this;
    }

    /**
     * Get the UBL document signatures.
     */
    public function getUBLDocumentSignatures(): ?UBLDocumentSignatures
    {
        return $this->UBLDocumentSignatures;
    }

    /**
     * Serializes this object to XML.
     *
     * @throws InvalidArgumentException if UBLDocumentSignatures is not set.
     */
    public function xmlSerialize(Writer $writer): void
    {
        if ($this->UBLDocumentSignatures === null) {
            throw new InvalidArgumentException('UBLDocumentSignatures must be set.');
        }

        $writer->write([
            Schema::SIG.'UBLDocumentSignatures' => $this->UBLDocumentSignatures,
        ]);
    }
}
