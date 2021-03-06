<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Text;

use Amp\Promise;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class NoControlCharacters implements Rule
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

            if (preg_match('~[\x00-\x1F\x7F\xA0]~', $value) === 0) {
                return succeed();
            }

            return fail('Text.NoControlCharacters');
        });
    }
}
