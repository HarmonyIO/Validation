<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Text;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

class IsAscii implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        return new Success(mb_check_encoding($value, 'ASCII'));
    }
}
