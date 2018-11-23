<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\CreditCard;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

class LuhnChecksum implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        return new Success($this->validateLuhn($value));
    }

    private function validateLuhn(string $value): bool
    {
        $total = 0;

        for ($i = 0; $i < strlen($value); $i++) {
            $digit = $value[$i];

            if ($i % 2 === strlen($value) % 2) {
                $digit *= 2;

                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $total += $digit;
        }

        return $total % 10 === 0;
    }
}
