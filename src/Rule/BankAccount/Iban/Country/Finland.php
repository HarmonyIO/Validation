<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Finland extends Country
{
    private const PATTERN = '~^FI\d{2}\d{6}\d{8}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'finland');
    }
}
