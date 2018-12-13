<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\CreditCard;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class LuhnChecksum implements Rule
{
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

            if ($this->validateLuhn($value)) {
                return succeed();
            }

            return fail(new Error('CreditCard.LuhnChecksum'));
        });
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
