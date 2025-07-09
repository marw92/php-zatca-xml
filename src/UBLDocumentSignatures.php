<?php

namespace Saleh7\Zatca;

use InvalidArgumentException;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

/**
 * Class UBLDocumentSignatures
 *
 * Represents UBL document signatures for XML serialization.
 */
class UBLDocumentSignatures implements XmlSerializable
{
    /** @var SignatureInformation|null Signature information. */
    private ?SignatureInformation $signatureInformation = null;

    /**
     * Set the signature information.
     */
    public function setSignatureInformation(SignatureInformation $signatureInformation): self
    {
        $this->signatureInformation = $signatureInformation;

        return $this;
    }

    /**
     * Serializes this object to XML.
     *
     * @param  Writer  $writer  The XML writer.
     *
     * @throws InvalidArgumentException if signature information is not set.
     */
    public function xmlSerialize(Writer $writer): void
    {
        if ($this->signatureInformation === null) {
            throw new InvalidArgumentException('Signature information must be set.');
        }
        $writer->write([
            Schema::SAC.'SignatureInformation' => $this->signatureInformation,
        ]);
    }
}
