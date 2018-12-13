<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Mauritania extends Country
{
    private const PATTERN = '~^MR13\d{5}\d{5}\d{11}\d{2}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'mauritania');
    }
}
