<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Estonia extends Country
{
    private const PATTERN = '~^EE\d{2}\d{2}\d{2}\d{12}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'estonia');
    }
}
