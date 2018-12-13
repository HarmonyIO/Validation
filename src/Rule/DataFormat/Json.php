<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\DataFormat;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Json implements Rule
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

            json_decode($value);

            if (json_last_error() === JSON_ERROR_NONE) {
                return succeed();
            }

            return fail(new Error('DataFormat.Json'));
        });
    }
}
