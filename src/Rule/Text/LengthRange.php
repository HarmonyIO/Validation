<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Text;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Exception\InvalidNumericalRange;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;

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
        if (!is_string($value)) {
            return new Success(false);
        }

        return call(function () use ($value) {
            if (!yield (new MinimumLength($this->minimumLength))->validate($value)) {
                return false;
            }

            return (new MaximumLength($this->maximumLength))->validate($value);
        });
    }
}
