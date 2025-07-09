<?php

namespace Saleh7\Zatca\Exceptions;

use Exception;
use Throwable;

/**
 * Class ZatcaException
 *
 * Base exception class for ZATCA-related errors.
 */
class ZatcaException extends \Sevaske\ZatcaApi\Exceptions\ZatcaException
{
    protected string $defaultMessage = 'An error occurred';

    public function __construct(?string $message = null, array $context = [], int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message ?? $this->getDefaultMessage(), $context, $code, $previous);
    }

    protected function getDefaultMessage(): string
    {
        return $this->defaultMessage;
    }
}
