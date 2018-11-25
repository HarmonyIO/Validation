<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\FileSystem\File;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\File\size;

class MinimumSize implements Rule
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
        if (!is_string($value)) {
            return new Success(false);
        }

        return call(function () use ($value) {
            if (!yield (new File())->validate($value)) {
                return false;
            }

            //phpcs:ignore SlevomatCodingStandard.PHP.UselessParentheses.UselessParentheses
            return (yield size($value)) >= $this->minimumSizeInBytes;
        });
    }
}
