<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Set;

use Amp\Promise;
use HarmonyIO\Validation\Rule\Rule;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Countable implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_countable($value)) {
            return fail('Set.Countable');
        }

        return succeed();
    }
}
