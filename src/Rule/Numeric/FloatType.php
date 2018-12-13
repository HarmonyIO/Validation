<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Numeric;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Combinator\Negate;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\BooleanType;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class FloatType implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(static function () use ($value) {
            /** @var Result $result */
            $result = yield (new Negate(new BooleanType()))->validate($value);

            if (!$result->isValid()) {
                return fail(new Error('Numeric.FloatType'));
            }

            if (filter_var($value, FILTER_VALIDATE_FLOAT) !== false) {
                return succeed();
            }

            return fail(new Error('Numeric.FloatType'));
        });
    }
}
