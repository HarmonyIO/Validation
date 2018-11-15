<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Numeric;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

class NumericType implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return new Success(is_numeric($value));
    }
}
