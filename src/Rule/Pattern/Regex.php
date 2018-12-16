<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Pattern;

use Amp\Promise;
use HarmonyIO\Validation\Exception\InvalidRegexPattern;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

class Regex implements Rule
{
    /** @var string */
    private $pattern;

    public function __construct(string $pattern)
    {
        $result = @preg_match($pattern, '');

        if ($result === false) {
            throw new InvalidRegexPattern($pattern);
        }

        $this->pattern = $pattern;
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

            if (@preg_match($this->pattern, $value) === 1) {
                return succeed();
            }

            return fail('Pattern.Regex');
        });
    }
}
