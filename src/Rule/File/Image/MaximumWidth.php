<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Image;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\ParallelFunctions\parallel;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class MaximumWidth implements Rule
{
    /** @var int */
    private $maximumWidth;

    public function __construct(int $maximumWidth)
    {
        $this->maximumWidth = $maximumWidth;
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
                return $result;
            }

            return parallel(function () use ($value) {
                // @codeCoverageIgnoreStart
                $imageSizeInformation = @getimagesize($value);

                if (!$imageSizeInformation || $imageSizeInformation[0] > $this->maximumWidth) {
                    return fail(new Error(
                        'File.Image.MaximumWidth',
                        new Parameter('width', $this->maximumWidth)
                    ));
                }

                return succeed();
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
