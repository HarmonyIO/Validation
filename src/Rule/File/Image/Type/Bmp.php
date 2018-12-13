<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Image\Type;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Combinator\Any;
use HarmonyIO\Validation\Rule\File\MimeType;
use HarmonyIO\Validation\Rule\FileSystem\File;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\ParallelFunctions\parallel;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Bmp implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(static function () use ($value) {
            /** @var Result $result */
            $result = yield (new File())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            $mimeTypeValidators = [
                new MimeType('image/bmp'),
                new MimeType('image/x-ms-bmp'),
            ];

            /** @var Result $result */
            $result = yield (new Any(...$mimeTypeValidators))->validate($value);

            if (!$result->isValid()) {
                return fail(new Error('File.Image.Type.Bmp'));
            }

            return parallel(static function () use ($value) {
                // @codeCoverageIgnoreStart
                $image = @imagecreatefrombmp($value);

                if ($image !== false) {
                    return succeed();
                }

                return fail(new Error('File.Image.Type.Bmp'));
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
