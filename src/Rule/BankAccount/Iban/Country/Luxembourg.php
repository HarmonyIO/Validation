<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\BankAccount\Iban\IbanChecksum;
use HarmonyIO\Validation\Rule\Rule;

final class Luxembourg implements Rule
{
    private const PATTERN = '~^LU\d{2}\d{3}[a-zA-Z0-9]{13}$~';

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        if (preg_match(self::PATTERN, $value, $matches) !== 1) {
            return new Success(false);
        }

        return (new IbanChecksum())->validate($value);
    }
}
