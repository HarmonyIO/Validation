<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Type;

use Amp\Promise;
use HarmonyIO\Validation\Rule\Rule;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class FloatType implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (is_float($value)) {
            return succeed();
        }

        return fail('Type.FloatType');
    }
}
