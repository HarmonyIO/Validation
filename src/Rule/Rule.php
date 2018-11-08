<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule;

use Amp\Promise;

interface Rule
{
    /**
     * @param mixed $value
     */
    public function validate($value): Promise;
}
