<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Isbn;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Combinator\Any;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\bubbleUp;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

class Isbn implements Rule
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
                return bubbleUp($result);
            }

            /** @var Result $result */
            $result = yield (new Any(new Isbn10(), new Isbn13()))->validate($value);

            if ($result->isValid()) {
                return succeed();
            }

            return fail(new Error('Isbn.Isbn'));
        });
    }
}
