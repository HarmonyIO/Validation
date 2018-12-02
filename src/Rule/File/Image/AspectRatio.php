<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Image;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Exception\InvalidAspectRatio;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\ParallelFunctions\parallel;

final class AspectRatio implements Rule
{
    /** @var float */
    private $ratio;

    public function __construct(string $aspectRatio)
    {
        if (preg_match('~^(?P<x>\d+(?:\.\d+)?):(?P<y>\d+(?:\.\d+)?)$~', $aspectRatio, $matches) !== 1) {
            throw new InvalidAspectRatio($aspectRatio);
        }

        $this->ratio = $matches['x'] / $matches['y'];
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        return call(function () use ($value) {
            // phpcs:ignore SlevomatCodingStandard.PHP.UselessParentheses.UselessParentheses
            if ((yield (new Image())->validate($value)) === false) {
                return false;
            }

            return parallel(function () use ($value) {
                // @codeCoverageIgnoreStart
                $imageSizeInformation = @getimagesize($value);

                if (!$imageSizeInformation) {
                    return false;
                }

                return $this->ratio === $imageSizeInformation[0] / $imageSizeInformation[1];
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
