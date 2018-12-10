<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class UnitedKingdom extends Country
{
    private const PATTERN = '~^GB\d{2}[A-Z]{4}\d{6}\d{8}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'unitedKingdom');
    }
}
