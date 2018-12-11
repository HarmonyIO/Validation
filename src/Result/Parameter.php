<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Result;

class Parameter
{
    /** @var string */
    private $key;

    /** @var mixed */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct(string $key, $value)
    {
        $this->key   = $key;
        $this->value = $value;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
