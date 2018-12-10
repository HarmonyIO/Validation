<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Result;

final class Result
{
    private $valid;

    private $errors;

    public function __construct(bool $valid, Error ...$errors)
    {
        $this->valid  = $valid;
        $this->errors = $errors;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
