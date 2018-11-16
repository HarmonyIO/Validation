<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Uuid;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

class Nil implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        return new Success($value === '00000000-0000-0000-0000-000000000000');
    }
}
