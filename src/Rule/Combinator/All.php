<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Combinator;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class All implements Rule
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
        $promises = array_reduce($this->rules, static function (array $promises, Rule $rule) use ($value) {
            $promises[] = $rule->validate($value);

            return $promises;
        }, []);

        return call(static function () use ($promises) {
            /** @var Result[] $results */
            $results = yield $promises;

            $errors = array_reduce($results, static function (array $errors, Result $result) {
                if ($result->isValid()) {
                    return $errors;
                }

                /** @var Error $error */
                foreach ($result->getErrors() as $error) {
                    $errors[] = $error;
                }

                return $errors;
            }, []);

            if (!$errors) {
                return succeed();
            }

            return fail(...$errors);
        });
    }
}
