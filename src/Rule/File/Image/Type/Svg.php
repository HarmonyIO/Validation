<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Image\Type;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\File\Image\Type\Svg\ValidAttributes;
use HarmonyIO\Validation\Rule\File\Image\Type\Svg\ValidElements;
use HarmonyIO\Validation\Rule\File\MimeType;
use HarmonyIO\Validation\Rule\FileSystem\File;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;

final class Svg implements Rule
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
            if (!yield (new File())->validate($value)) {
                return false;
            }

            if ((yield (new MimeType('image/svg'))->validate($value)) === false) {
                return false;
            }

            if ((yield (new ValidElements())->validate($value)) === false) {
                return false;
            }

            return yield (new ValidAttributes())->validate($value);
        });
    }
}
