<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\CreditCard;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

final class Discover implements Rule
{
    private const PATTERN = '~^65[4-9][0-9]{13}|64[4-9][0-9]{13}|6011[0-9]{12}|(622(?:12[6-9]|1[3-9][0-9]|[2-8][0-9][0-9]|9[01][0-9]|92[0-5])[0-9]{10})$~';

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
