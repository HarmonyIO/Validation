<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Result;

final class Result
{
    /** @var bool */
    private $valid;

    /** @var Error[] */
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

    public function getFirstError(): ?Error
    {
        if (!$this->errors) {
            return null;
        }

        return $this->errors[0];
    }

    /**
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
