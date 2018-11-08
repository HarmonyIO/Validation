<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Numeric;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

class IsInteger implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (is_bool($value)) {
            return new Success(false);
        }

        return new Success(filter_var($value, FILTER_VALIDATE_INT) !== false);
    }
}
