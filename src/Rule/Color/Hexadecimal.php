<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Color;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Hexadecimal implements Rule
{
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

            if (preg_match('~^#[[:xdigit:]]$~', $value) === 1) {
                return succeed();
            }

            return fail(new Error('Color.Hexadecimal'));
        });
    }
}
