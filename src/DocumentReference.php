<?php

namespace Saleh7\Zatca;

use DateTime;
use InvalidArgumentException;
use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

use function Sabre\Xml\Deserializer\keyValue;

class DocumentReference implements XmlDeserializable, XmlSerializable
{
    private $id;
    private DateTime $issueDate;
    private DateTime $issueTime;
    private int $documentTypeCode;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return static
     */
    public function setId(string $id): static
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getIssueDate(): DateTime
    {
        return $this->issueDate;
    }

    /**
     * @param DateTime $issueDate
     * @return static
     */
    public function setIssueDate(DateTime $issueDate): static
    {
        $this->issueDate = $issueDate;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getIssueTime(): DateTime
    {
        return $this->issueTime;
    }

    /**
     * @param DateTime $issueTime
     * @return static
     */
    public function setIssueTime(DateTime $issueTime): static
    {
        $this->issueTime = $issueTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getDocumentTypeCode(): int
    {
        return $this->documentTypeCode;
    }

    /**
     * @param int $documentTypeCode
     * @return static
     */
    public function setDocumentTypeCode(int $documentTypeCode): static
    {
        $this->documentTypeCode = $documentTypeCode;
        return $this;
    }

    /**
     * Validates required invoice data before XML serialization.
     *
     * @return void
     * @throws InvalidArgumentException if required data is missing.
     */
    public function validate(): void
    {
        if ($this->id === null) {
            throw new InvalidArgumentException('DocumentReference "ID" is required.');
        }
        if (!$this->issueDate instanceof DateTime) {
            throw new InvalidArgumentException('DocumentReference "IssueDate" must be a valid DateTime instance.');
        }
        if (!$this->issueTime instanceof DateTime) {
            throw new InvalidArgumentException('DocumentReference "IssueTime" must be a valid DateTime instance.');
        }
        if ($this->documentTypeCode === null) {
            throw new InvalidArgumentException('DocumentReference "DocumentTypeCode" is required.');
        }
        if (!in_array($this->documentTypeCode, InvoiceTypeCode::DOCUMENT_TYPE_CODES)){
            throw new InvalidArgumentException(
                sprintf(
                    'DocumentTypeCode "%s" is not valid. Allowed values: %s',
                    $this->documentTypeCode,
                    implode(', ', InvoiceTypeCode::DOCUMENT_TYPE_CODES)
                )
            );
        }
    }

    /**
     * The xmlSerialize method is called during xml writing.
     * 
     * @param Writer $writer
     * @return void
     * @throws InvalidArgumentException if required data is missing.
     */
    public function xmlSerialize(Writer $writer): void
    {
        $this->validate();

        $writer->write([Schema::CBC . 'ID' => $this->id]);
        $writer->write([Schema::CBC.'IssueDate' => $this->issueDate->format('Y-m-d')]);
        $writer->write([Schema::CBC.'IssueTime' => $this->issueTime->format('H:i:s')]);
        $writer->write([
            Schema::CBC . 'DocumentTypeCode' => $this->documentTypeCode,
        ]);
    }

    /**
     * The xmlDeserialize method is called during xml reading.
     * 
     * @param Reader $reader
     * @return static
     */
    public static function xmlDeserialize(Reader $reader): static
    {
        $keyValues = keyValue($reader);

        $instance = new static();

        $instance->setId($keyValues[Schema::CBC . 'ID'] ?? null);
        if (!empty($keyValues[Schema::CBC . 'IssueDate'])) {
            $instance->setIssueDate(new DateTime($keyValues[Schema::CBC . 'IssueDate']));
        }
        if (!empty($keyValues[Schema::CBC . 'IssueTime'])) {
            $instance->setIssueTime(new DateTime($keyValues[Schema::CBC . 'IssueTime']));
        }
        $instance->setDocumentTypeCode($keyValues[Schema::CBC . 'DocumentTypeCode'] ?? null);

        return $instance;
    }
}