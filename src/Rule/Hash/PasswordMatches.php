<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Hash;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function Amp\ParallelFunctions\parallel;
use function HarmonyIO\Validation\bubbleUp;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class PasswordMatches implements Rule
{
    /** @var string */
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
        return call(function() use ($value) {
            /** @var Result $result */
            $result = yield (new StringType())->validate($value);

            if (!$result->isValid()) {
                return bubbleUp($result);
            }

            return parallel(function () use ($value) {
                // @codeCoverageIgnoreStart
                if (password_verify($value, $this->hash)) {
                    return succeed();
                }

                return fail(new Error('Hash.PasswordMatches'));
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
