<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Bahrain extends Country
{
    private const PATTERN = '~^BH\d{2}[A-Z]{4}[a-zA-Z0-9]{14}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'bahrain');
    }
}
