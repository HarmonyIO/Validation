<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Image\Type;

use Amp\Promise;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Combinator\All;
use HarmonyIO\Validation\Rule\File\Image\Type\Svg\ValidAttributes;
use HarmonyIO\Validation\Rule\File\Image\Type\Svg\ValidElements;
use HarmonyIO\Validation\Rule\File\MimeType;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;

final class Svg implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(static function () use ($value) {
            /** @var Result $result */
            $result = yield (new MimeType('image/svg'))->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            return (new All(new ValidElements(), new ValidAttributes()))->validate($value);
        });
    }
}
