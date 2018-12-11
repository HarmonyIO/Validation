<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\CreditCard;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\bubbleUp;
use function HarmonyIO\Validation\fail;

final class AmericanExpress implements Rule
{
    private const PATTERN = '~^3[47][0-9]{13}$~';

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(static function () use ($value) {
            /** @var Result $result */
            $result = yield (new StringType())->validate($value);

            if (!$result->isValid()) {
                return bubbleUp($result);
            }

            if (preg_match(self::PATTERN, $value) !== 1) {
                return fail(new Error('CreditCard.AmericanExpress'));
            }

            return (new LuhnChecksum())->validate($value);
        });
    }
}
