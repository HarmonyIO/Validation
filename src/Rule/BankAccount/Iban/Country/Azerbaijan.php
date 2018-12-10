<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Azerbaijan extends Country
{
    private const PATTERN = '~^AZ\d{2}[A-Z]{4}[a-zA-Z0-9]{20}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'azerbaijan');
    }
}
