<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\DataFormat;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

final class Json implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        json_decode($value);

        return new Success(json_last_error() === JSON_ERROR_NONE);
    }
}
