<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Iban;
use HarmonyIO\Validation\Rule\Rule;

class Belgium extends Iban implements Rule
{
    private const PATTERN = '~^BE\d{2}\d{3}\d{7}\d{2}$~';

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
