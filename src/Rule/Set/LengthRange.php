<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Set;

use Amp\Promise;
use HarmonyIO\Validation\Exception\InvalidNumericalRange;
use HarmonyIO\Validation\Rule\Combinator\All;
use HarmonyIO\Validation\Rule\Rule;

final class LengthRange implements Rule
{
    /** @var int */
    private $minimumLength;

    /** @var int */
    private $maximumLength;

    public function __construct(int $minimumLength, int $maximumLength)
    {
        if ($minimumLength > $maximumLength) {
            throw new InvalidNumericalRange($minimumLength, $maximumLength);
        }

        $this->minimumLength = $minimumLength;
        $this->maximumLength = $maximumLength;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return (new All(new MinimumLength($this->minimumLength), new MaximumLength($this->maximumLength)))
            ->validate($value)
        ;
    }
}
