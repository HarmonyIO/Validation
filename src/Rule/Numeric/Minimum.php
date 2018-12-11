<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Numeric;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function HarmonyIO\Validation\bubbleUp;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Minimum implements Rule
{
    /** @var int */
    private $minimum;

    public function __construct(int $minimum)
    {
        $this->minimum = $minimum;
    }

    public function getErrorMessage(): string
    {
        return 'numeric.minimum{minimum}';
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new NumericType())->validate($value);

            if (!$result->isValid()) {
                return bubbleUp($result);
            }

            if ($value >= $this->minimum) {
                return succeed();
            }

            return fail(new Error('Numeric.Minimum', new Parameter('minimum', $this->minimum)));
        });
    }
}
