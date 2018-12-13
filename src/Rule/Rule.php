<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule;

use Amp\Promise;

interface Rule
{
    /**
     * @param mixed $value
     * @return Promise<HarmonyIO\Validation\Result\Result>
     */
    public function validate($value): Promise;
}
