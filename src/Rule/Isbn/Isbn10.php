<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Isbn;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

class Isbn10 implements Rule
{
    private const PATTERN = '~^\d{9}[\dx]$~i';

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

        $sum = 0;

        for ($i = 0; $i < 10; $i++) {
            $currentValue = $value[$i];

            if (strtolower($value[$i]) === 'x') {
                $currentValue = 10;
            }

            $sum += $currentValue * (10 - $i);
        }

        return new Success($sum % 11 === 0);
    }
}
