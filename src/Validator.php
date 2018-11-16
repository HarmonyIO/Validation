<?php declare(strict_types=1);

namespace HarmonyIO\Validation;

use Amp\Promise;
use HarmonyIO\Validation\Rule\Rule;

class Validator
{
    /** @var Rule */
    private $rule;

    public function __construct(Rule $rule)
    {
        $this->rule = $rule;
    }

    /**
     * @param mixed $value
     */
    public function validate($value): Promise
    {
        return $this->rule->validate($value);
    }
}
