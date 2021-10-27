<?php

namespace Mpyw\UuidUlidConverter;

final class BadFormatException extends \RuntimeException implements ConversionException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
