<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Hash;

use Amp\Promise;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class HashMatches implements Rule
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
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new StringType())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            if (hash_equals($this->knownString, $value)) {
                return succeed();
            }

            return fail('Hash.HashMatches');
        });
    }
}
