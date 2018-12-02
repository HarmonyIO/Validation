<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Combinator;

use Amp\Promise;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;

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
            return !yield $this->rule->validate($value);
        });
    }
}
