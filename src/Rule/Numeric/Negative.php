<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Numeric;

use Amp\Promise;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;

final class Negative implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(static function () use ($value) {
            if (!yield (new NumericType())->validate($value)) {
                return false;
            }

            return $value < 0;
        });
    }
}
