<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Combinator;

use Amp\Promise;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function HarmonyIO\Validation\failWithError;
use function HarmonyIO\Validation\succeed;

final class AtLeast implements Rule
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
            return succeed();
        }

        $promises = array_reduce($this->rules, static function (array $promises, Rule $rule) use ($value) {
            $promises[] = $rule->validate($value);

            return $promises;
        }, []);

        return call(function () use ($promises) {
            /** @var Result[] $results */
            $results = yield $promises;

            $validRules = 0;
            $errors     = [];

            foreach ($results as $result) {
                if ($result->isValid()) {
                    $validRules++;
                }

                $errors = array_merge($errors, $result->getErrors());
            }

            if ($validRules >= $this->minimumNumberOfValidRules) {
                return succeed();
            }

            return failWithError(...$errors);
        });
    }
}
