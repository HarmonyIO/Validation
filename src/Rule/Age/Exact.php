<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Age;

use Amp\Promise;
use HarmonyIO\Validation\Rule\Rule;

final class Exact implements Rule
{
    /** @var int */
    private $age;

    public function __construct(int $age)
    {
        $this->age = $age;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return (new Range($this->age, $this->age))->validate($value);
    }
}
