<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Numeric;

use Amp\Promise;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Maximum implements Rule
{
    /** @var int */
    private $maximum;

    public function __construct(float $maximum)
    {
        $this->maximum = $maximum;
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

            if ($value <= $this->maximum) {
                return succeed();
            }

            return fail('Numeric.Maximum', new Parameter('maximum', $this->maximum));
        });
    }
}
