<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Image;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Combinator\Any;
use HarmonyIO\Validation\Rule\File\MimeType;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\File\exists;
use function Amp\ParallelFunctions\parallel;

class Bmp implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        return call(static function () use ($value) {
            if (!yield exists($value)) {
                return false;
            }

            $mimeTypeValidators = [
                new MimeType('image/bmp'),
                new MimeType('image/x-ms-bmp'),
            ];

            if ((yield (new Any(...$mimeTypeValidators))->validate($value)) === false) {
                return false;
            }

            return parallel(static function () use ($value) {
                // @codeCoverageIgnoreStart
                $image = @imagecreatefrombmp($value);

                return $image !== false;
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
