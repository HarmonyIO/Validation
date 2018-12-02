<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Image;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\File\Image\Type\Bmp;
use HarmonyIO\Validation\Rule\File\Image\Type\Gif;
use HarmonyIO\Validation\Rule\File\Image\Type\Jpeg;
use HarmonyIO\Validation\Rule\File\Image\Type\Png;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;

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
        if (!is_string($value)) {
            return new Success(false);
        }

        return call(static function () use ($value) {
            foreach (self::IMAGE_TYPES as $ruleFullyQualifiedClassName) {
                /** @var Rule $rule */
                $rule = new $ruleFullyQualifiedClassName();

                // phpcs:ignore SlevomatCodingStandard.PHP.UselessParentheses.UselessParentheses
                if ((yield $rule->validate($value)) === true) {
                    return true;
                }
            }

            return false;
        });
    }
}
