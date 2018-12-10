<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule;

use Amp\Promise;
use HarmonyIO\Validation\Result\Result;

interface Rule
{
    /**
     * @param mixed $value
     * @return Promise<Result>
     */
    public function validate($value): Promise;
}
