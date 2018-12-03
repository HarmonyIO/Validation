<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Exception;

class InvalidNumericValue extends Exception
{
    private const MESSAGE = 'Value (`%s`) must be a numeric value.';

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        parent::__construct(sprintf(self::MESSAGE, $value));
    }
}
