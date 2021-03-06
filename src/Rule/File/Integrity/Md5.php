<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Integrity;

use Amp\Promise;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\FileSystem\File;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\ParallelFunctions\parallel;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Md5 implements Rule
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
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new File())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            return parallel(function () use ($value) {
                // @codeCoverageIgnoreStart
                if (hash_equals($this->hash, md5_file($value))) {
                    return succeed();
                }

                return fail('File.Integrity.Md5');
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
