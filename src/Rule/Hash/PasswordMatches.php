<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Hash;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\ParallelFunctions\parallel;

class PasswordMatches implements Rule
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
        if (!is_string($value)) {
            return new Success(false);
        }

        return parallel(function () use ($value) {
            // @codeCoverageIgnoreStart
            return password_verify($value, $this->hash);
            // @codeCoverageIgnoreEnd
        })();
    }
}
