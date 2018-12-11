<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Image;

use Amp\Promise;
use HarmonyIO\Validation\Exception\InvalidAspectRatio;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\ParallelFunctions\parallel;
use function HarmonyIO\Validation\bubbleUp;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class AspectRatio implements Rule
{
    /** @var string */
    private $x;

    /** @var string */
    private $y;

    /** @var float */
    private $ratio;

    public function __construct(string $aspectRatio)
    {
        if (preg_match('~^(?P<x>\d+(?:\.\d+)?):(?P<y>\d+(?:\.\d+)?)$~', $aspectRatio, $matches) !== 1) {
            throw new InvalidAspectRatio($aspectRatio);
        }

        $this->x = $matches['x'];
        $this->y = $matches['y'];

        $this->ratio = $matches['x'] / $matches['y'];
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new Image())->validate($value);

            if (!$result->isValid()) {
                return bubbleUp($result);
            }

            return parallel(function () use ($value) {
                // @codeCoverageIgnoreStart
                $imageSizeInformation = @getimagesize($value);

                if (!$imageSizeInformation || $this->ratio !== $imageSizeInformation[0] / $imageSizeInformation[1]) {
                    return fail(new Error(
                        'File.Image.AspectRatio',
                        new Parameter('ratio', sprintf('%s:%s', $this->x, $this->y))
                    ));
                }

                return succeed();
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
