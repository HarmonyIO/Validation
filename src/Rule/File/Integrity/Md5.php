<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Integrity;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\File\exists;
use function Amp\ParallelFunctions\parallel;

class Md5 implements Rule
{
    /** @var string string */
    private $hash;

    public function __construct(string $hash)
    {
        $this->hash = $hash;
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
            if (!yield exists($value)) {
                return false;
            }

            return parallel(function () use ($value) {
                // @codeCoverageIgnoreStart
                return hash_equals($this->hash, md5_file($value));
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
