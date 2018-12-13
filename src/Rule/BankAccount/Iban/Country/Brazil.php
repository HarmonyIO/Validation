<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Brazil extends Country
{
    private const PATTERN = '~^BR\d{2}\d{8}\d{5}\d{10}[A-Z]{1}[a-zA-Z0-9]{1}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'brazil');
    }
}
