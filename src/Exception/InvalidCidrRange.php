<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Exception;

final class InvalidCidrRange extends Exception
{
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);
    }
}
