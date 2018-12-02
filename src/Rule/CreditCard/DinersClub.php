<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\CreditCard;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

final class DinersClub implements Rule
{
    private const PATTERN = '~^3(?:0[0-5]|[68][0-9])[0-9]{11}$~';

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        if (preg_match(self::PATTERN, $value) !== 1) {
            return new Success(false);
        }

        return (new LuhnChecksum())->validate($value);
    }
}
