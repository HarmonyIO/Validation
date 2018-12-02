<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Age;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Exception\InvalidAgeRange;
use HarmonyIO\Validation\Rule\Combinator\All;
use HarmonyIO\Validation\Rule\Rule;

final class Range implements Rule
{
    /** @var int */
    private $minimumAge;

    /** @var int */
    private $maximumAge;

    public function __construct(int $minimumAge, int $maximumAge)
    {
        if ($minimumAge > $maximumAge) {
            throw new InvalidAgeRange($minimumAge, $maximumAge);
        }

        $this->minimumAge = $minimumAge;
        $this->maximumAge = $maximumAge;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!$value instanceof \DateTimeInterface) {
            return new Success(false);
        }

        return (new All(new Minimum($this->minimumAge), new Maximum($this->maximumAge)))->validate($value);
    }
}
