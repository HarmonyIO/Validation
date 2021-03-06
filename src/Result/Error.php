<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Result;

final class Error
{
    /** @var string */
    private $message;

    /** @var Parameter[] */
    private $parameters = [];

    public function __construct(string $message, Parameter ...$parameters)
    {
        $this->message    = $message;
        $this->parameters = $parameters;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return Parameter[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
