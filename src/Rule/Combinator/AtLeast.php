<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Combinator;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;

class AtLeast implements Rule
{
    /** @var int */
    private $minimumNumberOfValidRules;

    /** @var Rule[] */
    private $rules = [];

    public function __construct(int $minimumNumberOfValidRules, Rule ...$rules)
    {
        $this->minimumNumberOfValidRules = $minimumNumberOfValidRules;
        $this->rules                     = $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if ($this->minimumNumberOfValidRules === 0) {
            return new Success(true);
        }

        return call(function () use ($value) {
            $validRules = 0;

            foreach ($this->rules as $rule) {
                if (yield $rule->validate($value)) {
                    $validRules++;
                }

                if ($validRules === $this->minimumNumberOfValidRules) {
                    return true;
                }
            }

            return false;
        });
    }
}
