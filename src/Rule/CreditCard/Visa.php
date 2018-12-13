<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\CreditCard;

use Amp\Promise;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\fail;

final class Visa implements Rule
{
    private const PATTERN = '~^4[0-9]{12}(?:[0-9]{3})?$~';

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
                return fail('CreditCard.Visa');
            }

            return (new LuhnChecksum())->validate($value);
        });
    }
}
