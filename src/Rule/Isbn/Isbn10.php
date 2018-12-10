<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Isbn;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\bubbleUp;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

class Isbn10 implements Rule
{
    private const PATTERN = '~^\d{9}[\dx]$~i';

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(function() use ($value) {
            /** @var Result $result */
            $result = yield (new StringType())->validate($value);

            if (!$result->isValid()) {
                return bubbleUp($result);
            }

            if (preg_match(self::PATTERN, $value) !== 1) {
                return fail(new Error('Isbn.Isbn10'));
            }

            if ($this->isCheckDigitValid($value)) {
                return succeed();
            }

            return fail(new Error('Isbn.Isbn10'));
        });
    }

    private function isCheckDigitValid($value): bool
    {
        $sum = 0;

        for ($i = 0; $i < 10; $i++) {
            $currentValue = $value[$i];

            if (strtolower($value[$i]) === 'x') {
                $currentValue = 10;
            }

            $sum += $currentValue * (10 - $i);
        }

        return $sum % 11 === 0;
    }
}
