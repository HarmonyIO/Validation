<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Email;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

final class NativeEmailAddress implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        return new Success(filter_var($value, FILTER_VALIDATE_EMAIL) !== false);
    }
}
