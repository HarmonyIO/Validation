<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Croatia extends Country
{
    private const PATTERN = '~^HR\d{2}\d{7}\d{10}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'croatia');
    }
}
