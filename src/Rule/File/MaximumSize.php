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
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class MaximumSize implements Rule
{
    /** @var int */
    private $maximumSizeInBytes;

    public function __construct(int $maximumSizeInBytes)
    {
        $this->maximumSizeInBytes = $maximumSizeInBytes;
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
                return $result;
            }

            //phpcs:ignore SlevomatCodingStandard.PHP.UselessParentheses.UselessParentheses
            if ((yield size($value)) <= $this->maximumSizeInBytes) {
                return succeed();
            }

            return fail(new Error(
                'File.MaximumSize',
                new Parameter('size', $this->maximumSizeInBytes)
            ));
        });
    }
}
