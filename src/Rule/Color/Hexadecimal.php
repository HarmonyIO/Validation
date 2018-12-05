<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Color;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

final class Hexadecimal implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        return new Success(preg_match('~^#[[:xdigit:]]$~', $value) === 1);
    }
}
