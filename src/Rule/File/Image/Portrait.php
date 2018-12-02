<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Image;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\ParallelFunctions\parallel;

final class Portrait implements Rule
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
            // phpcs:ignore SlevomatCodingStandard.PHP.UselessParentheses.UselessParentheses
            if ((yield (new Image())->validate($value)) === false) {
                return false;
            }

            return parallel(static function () use ($value) {
                // @codeCoverageIgnoreStart
                $imageSizeInformation = @getimagesize($value);

                if (!$imageSizeInformation) {
                    return false;
                }

                return $imageSizeInformation[0] < $imageSizeInformation[1];
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
