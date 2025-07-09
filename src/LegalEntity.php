<?php

namespace Saleh7\Zatca;

use InvalidArgumentException;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

/**
 * Class LegalEntity
 *
 * Represents a legal entity with registration details for XML serialization.
 */
class LegalEntity implements XmlSerializable
{
    /** @var string|null Registration name of the legal entity. */
    private ?string $registrationName = null;

    /**
     * Set the registration name.
     *
     * @throws InvalidArgumentException if the registration name is an empty string.
     */
    public function setRegistrationName(?string $registrationName): self
    {
        if ($registrationName !== null && trim($registrationName) === '') {
            throw new InvalidArgumentException('Registration name cannot be empty.');
        }
        $this->registrationName = $registrationName;

        return $this;
    }

    /**
     * Get the registration name.
     */
    public function getRegistrationName(): ?string
    {
        return $this->registrationName;
    }

    /**
     * Serializes this object to XML.
     */
    public function xmlSerialize(Writer $writer): void
    {
        if ($this->registrationName !== null) {
            $writer->write([
                Schema::CBC.'RegistrationName' => $this->registrationName,
            ]);
        }
    }
}
