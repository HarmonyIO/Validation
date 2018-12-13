<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Portugal extends Country
{
    private const PATTERN = '~^PT50\d{4}\d{4}\d{11}\d{2}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'portugal');
    }
}
