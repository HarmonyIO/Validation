<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Italy extends Country
{
    private const PATTERN = '~^IT\d{2}[A-Z]{1}\d{5}\d{5}[a-zA-Z0-9]{12}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'italy');
    }
}
