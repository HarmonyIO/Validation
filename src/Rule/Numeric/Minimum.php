<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Numeric;

use Amp\Promise;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Minimum implements Rule
{
    /** @var int */
    private $minimum;

    public function __construct(float $minimum)
    {
        $this->minimum = $minimum;
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
                return $result;
            }

            if ($value >= $this->minimum) {
                return succeed();
            }

            return fail('Numeric.Minimum', new Parameter('minimum', $this->minimum));
        });
    }
}
