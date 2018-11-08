<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Hash;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

class HashMatches implements Rule
{
    /** @var string */
    private $knownString;

    public function __construct(string $knownString)
    {
        $this->knownString = $knownString;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        return new Success(hash_equals($this->knownString, $value));
    }
}
