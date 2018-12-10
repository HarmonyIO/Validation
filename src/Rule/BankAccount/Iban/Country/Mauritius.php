<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Mauritius extends Country
{
    private const PATTERN = '~^MU\d{2}[A-Z]{4}[a-zA-Z0-9]{22}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'mauritius');
    }
}
