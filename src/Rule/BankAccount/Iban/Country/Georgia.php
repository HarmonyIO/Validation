<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Georgia extends Country
{
    private const PATTERN = '~^GE\d{2}[A-Z]{2}\d{16}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'georgia');
    }
}
