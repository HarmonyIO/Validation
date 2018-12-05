<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule;

use Amp\Promise;

interface Rule
{
    /**
     * @param mixed $value
     * @return Promise<bool>
     */
    public function validate($value): Promise;
}
