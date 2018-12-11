<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Image;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Combinator\Any;
use HarmonyIO\Validation\Rule\File\Image\Type\Bmp;
use HarmonyIO\Validation\Rule\File\Image\Type\Gif;
use HarmonyIO\Validation\Rule\File\Image\Type\Jpeg;
use HarmonyIO\Validation\Rule\File\Image\Type\Png;
use HarmonyIO\Validation\Rule\FileSystem\File;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function HarmonyIO\Validation\bubbleUp;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Image implements Rule
{
    private const IMAGE_TYPES = [
        Bmp::class,
        Gif::class,
        Jpeg::class,
        Png::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(static function () use ($value) {
            /** @var Result $result */
            $result = yield (new File())->validate($value);

            if (!$result->isValid()) {
                return bubbleUp($result);
            }

            $imageTypes = [];

            foreach (self::IMAGE_TYPES as $type) {
                $imageTypes[] = new $type();
            }

            /** @var Result $result */
            $result = yield (new Any(...$imageTypes))->validate($value);

            if ($result->isValid()) {
                return succeed();
            }

            return fail(new Error('File.Image.Image'));
        });
    }
}
