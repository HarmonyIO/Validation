<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Austria extends Country
{
    private const PATTERN = '~^AT\d{2}\d{5}\d{11}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'austria');
    }
}
