<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Macedonia extends Country
{
    private const PATTERN = '~^MK07\d{3}[a-zA-Z0-9]{12}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'macedonia');
    }
}
