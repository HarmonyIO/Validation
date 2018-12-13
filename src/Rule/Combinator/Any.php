<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Combinator;

use Amp\Promise;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function HarmonyIO\Validation\failWithError;
use function HarmonyIO\Validation\succeed;

final class Any implements Rule
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
        if (count($this->rules) === 0) {
            return succeed();
        }

        $promises = array_reduce($this->rules, static function (array $promises, Rule $rule) use ($value) {
            $promises[] = $rule->validate($value);

            return $promises;
        }, []);

        return call(function () use ($promises) {
            /** @var Result[] $results */
            $results = yield $promises;

            $valid  = false;
            $errors = [];

            foreach ($results as $result) {
                if ($result->isValid()) {
                    $valid = true;
                }

                $errors = array_merge($errors, $result->getErrors());
            }

            if ($valid) {
                return succeed();
            }

            return failWithError(...$errors);
        });
    }
}
