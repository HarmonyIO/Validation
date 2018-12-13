<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Isbn;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

class Isbn13 implements Rule
{
    private const PATTERN = '~^\d{13}$~';

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new StringType())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            if (preg_match(self::PATTERN, $value) !== 1) {
                return fail(new Error('Isbn.Isbn13'));
            }

            if ($this->isCheckDigitValid($value)) {
                return succeed();
            }

            return fail(new Error('Isbn.Isbn13'));
        });
    }

    private function isCheckDigitValid(string $value): bool
    {
        $sum = 0;

        for ($i = 0; $i < 13; $i++) {
            $currentValue = $value[$i];

            if ($i % 2 === 1) {
                $currentValue *= 3;
            }

            $sum += $currentValue;
        }

        return $sum % 10 === 0;
    }
}
