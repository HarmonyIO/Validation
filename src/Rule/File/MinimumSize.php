<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\FileSystem\File;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\File\size;
use function HarmonyIO\Validation\bubbleUp;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class MinimumSize implements Rule
{
    /** @var int */
    private $minimumSizeInBytes;

    public function __construct(int $minimumSizeInBytes)
    {
        $this->minimumSizeInBytes = $minimumSizeInBytes;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new File())->validate($value);

            if (!$result->isValid()) {
                return bubbleUp($result);
            }

            //phpcs:ignore SlevomatCodingStandard.PHP.UselessParentheses.UselessParentheses
            if ((yield size($value)) >= $this->minimumSizeInBytes) {
                return succeed();
            }

            return fail(new Error(
                'File.MinimumSize',
                new Parameter('size', $this->minimumSizeInBytes)
            ));
        });
    }
}
