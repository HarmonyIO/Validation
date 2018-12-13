<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class UnitedArabEmirates extends Country
{
    private const PATTERN = '~^AE\d{2}\d{3}\d{16}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'unitedArabEmirates');
    }
}
