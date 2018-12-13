<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Numeric;

use Amp\Promise;
use HarmonyIO\Validation\Exception\InvalidNumericalRange;
use HarmonyIO\Validation\Exception\InvalidNumericValue;
use HarmonyIO\Validation\Rule\Combinator\All;
use HarmonyIO\Validation\Rule\Rule;

final class Range implements Rule
{
    /** @var mixed */
    private $minimumValue;

    /** @var mixed */
    private $maximumValue;

    /**
     * @param mixed $minimumValue
     * @param mixed $maximumValue
     */
    public function __construct($minimumValue, $maximumValue)
    {
        if (!is_numeric($minimumValue)) {
            throw new InvalidNumericValue($minimumValue);
        }

        if (!is_numeric($maximumValue)) {
            throw new InvalidNumericValue($maximumValue);
        }

        if ($minimumValue > $maximumValue) {
            throw new InvalidNumericalRange($minimumValue, $maximumValue);
        }

        $this->minimumValue = $minimumValue;
        $this->maximumValue = $maximumValue;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return (new All(new Minimum($this->minimumValue), new Maximum($this->maximumValue)))->validate($value);
    }
}
