<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Ireland extends Country
{
    private const PATTERN = '~^IE\d{2}[A-Z]{4}\d{6}\d{8}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'ireland');
    }
}
