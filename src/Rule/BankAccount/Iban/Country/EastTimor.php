<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Iban;
use HarmonyIO\Validation\Rule\Rule;

class EastTimor extends Iban implements Rule
{
    private const PATTERN = '~^TL38\d{3}\d{16}$~';

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

        return new Success($this->validateChecksum($value));
    }
}
