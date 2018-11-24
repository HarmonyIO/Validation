<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Image\Type;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\File\MimeType;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\File\exists;
use function Amp\ParallelFunctions\parallel;

class Jpeg implements Rule
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

            if ((yield (new MimeType('image/jpeg'))->validate($value)) === false) {
                return false;
            }

            return parallel(static function () use ($value) {
                // @codeCoverageIgnoreStart
                $image = @imagecreatefromjpeg($value);

                return $image !== false;
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
