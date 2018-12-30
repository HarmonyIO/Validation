<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Exception;

final class InvalidNumericalRange extends Exception
{
    private const MESSAGE_TEMPLATE = 'The minimum (`%s`) can not be greater than the maximum (`%s`).';

    /**
     * @param mixed $minimum
     * @param mixed $maximum
     * @throws InvalidNumericValue
     */
    public function __construct($minimum, $maximum)
    {
        if (!is_numeric($minimum)) {
            throw new InvalidNumericValue($minimum);
        }

        if (!is_numeric($maximum)) {
            throw new InvalidNumericValue($maximum);
        }

        parent::__construct(sprintf(self::MESSAGE_TEMPLATE, $minimum, $maximum));
    }
}
