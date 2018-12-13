<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Text;

use Amp\Promise;
use HarmonyIO\Validation\Exception\InvalidNumericalRange;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Combinator\All;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\bubbleUp;

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
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new StringType())->validate($value);

            if (!$result->isValid()) {
                return bubbleUp($result);
            }

            return (new All(
                new MinimumLength($this->minimumLength),
                new MaximumLength($this->maximumLength)
            ))->validate($value);
        });
    }
}
