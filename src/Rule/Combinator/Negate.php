<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Combinator;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Negate implements Rule
{
    /** @var Rule */
    private $rule;

    public function __construct(Rule $rule)
    {
        $this->rule = $rule;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield $this->rule->validate($value);

            if (!$result->isValid()) {
                return succeed();
            }

            return fail(new Error('Combinator.Negate'));
        });
    }
}
