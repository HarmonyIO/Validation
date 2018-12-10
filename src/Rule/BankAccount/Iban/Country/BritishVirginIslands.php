<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class BritishVirginIslands extends Country
{
    private const PATTERN = '~^VG\d{2}[A-Z]{4}\d{16}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'britishVirginIslands');
    }
}
