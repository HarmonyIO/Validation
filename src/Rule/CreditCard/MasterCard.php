<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\CreditCard;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\fail;

final class MasterCard implements Rule
{
    private const PATTERN = '~^(5[1-5][0-9]{14}|2(22[1-9][0-9]{12}|2[3-9][0-9]{13}|[3-6][0-9]{14}|7[0-1][0-9]{13}|720[0-9]{12}))$~';

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(static function () use ($value) {
            /** @var Result $result */
            $result = yield (new StringType())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            if (preg_match(self::PATTERN, $value) !== 1) {
                return fail(new Error('CreditCard.MasterCard'));
            }

            return (new LuhnChecksum())->validate($value);
        });
    }
}
