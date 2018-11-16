<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\NationalId;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

class Bsn implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!$this->validateValueType($value)) {
            return new Success(false);
        }

        $value = (string) $value;

        if (strlen($value) !== 9) {
            return new Success(false);
        }

        $sum = 0;

        for ($i = 9; $i > 1; $i--) {
            $sum += $i * $value[9 - $i];
        }

        $sum += $value[8] * -1;

        return new Success($sum !== 0 && $sum % 11 === 0);
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
}
