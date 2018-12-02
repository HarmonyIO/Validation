<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

final class IbanChecksum implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        return new Success($this->validateChecksum($value));
    }

    protected function validateChecksum(string $value): bool
    {
        // move first 4 characters to the end of the string
        $value = $this->reArrangeString($value);

        // replace each letter with the corresponding 2 digit equivalent
        $value = $this->replaceLetters($value);

        // calculate the remainder of 97
        // if the IBAN is valid the remainder MUST be 1
        return $this->calculateRemainder($value) === 1;
    }

    private function reArrangeString(string $value): string
    {
        return substr($value, 4) . substr($value, 0, 4);
    }

    private function replaceLetters(string $value): string
    {
        $replacedValue = '';

        foreach (str_split($value) as $char) {
            if (ord($char) >= 65 && ord($char) <= 90) {
                $char = ord($char) - 55;
            }

            $replacedValue .= $char;
        }

        return $replacedValue;
    }

    // piece wise modulo calculation to prevent overflowing
    private function calculateRemainder(string $value): int
    {
        $remainder = null;
        $sliceSize = 9;

        for ($i = 0; $i < strlen($value); $i += $sliceSize) {
            if ($i > 0) {
                $sliceSize = 7;
            }

            $part = $remainder . substr($value, $i, $sliceSize);

            $remainder = intval($part) % 97;
        }

        return $remainder;
    }
}
