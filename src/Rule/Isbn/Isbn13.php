<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Isbn;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

class Isbn13 implements Rule
{
    private const PATTERN = '~^\d{13}$~';

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

        for ($i = 0; $i < 13; $i++) {
            $currentValue = $value[$i];

            if ($i % 2 === 1) {
                $currentValue *= 3;
            }

            $sum += $currentValue;
        }

        return new Success($sum % 10 === 0);
    }
}
