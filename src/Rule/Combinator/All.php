<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Combinator;

use Amp\Promise;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;

class All implements Rule
{
    /** @var Rule[] */
    private $rules = [];

    public function __construct(Rule ...$rules)
    {
        $this->rules = $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(function () use ($value) {
            foreach ($this->rules as $rule) {
                if (!yield $rule->validate($value)) {
                    return false;
                }
            }

            return true;
        });
    }
}
