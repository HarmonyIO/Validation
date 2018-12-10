<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\NationalId;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Bsn implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(function() use ($value) {
            if (!$this->validateValueType($value)) {
                return fail(new Error('NationalId.Bsn'));
            }

            $value = (string) $value;

            if (strlen($value) !== 9) {
                return fail(new Error('NationalId.Bsn'));
            }

            if ($this->isCheckDigitValid($value)) {
                return succeed();
            }

            return fail(new Error('NationalId.Bsn'));;
        });
    }

    /**
     * @param mixed $value
     */
    private function validateValueType($value): bool
    {
        if (is_int($value)) {
            return true;
        }

        return is_string($value) && ctype_digit($value);
    }

    private function isCheckDigitValid(string $value): bool
    {
        $sum = 0;

        for ($i = 9; $i > 1; $i--) {
            $sum += $i * $value[9 - $i];
        }

        $sum += $value[8] * -1;

        return $sum !== 0 && $sum % 11 === 0;
    }
}
