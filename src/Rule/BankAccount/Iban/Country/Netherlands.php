<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Netherlands extends Country
{
    private const PATTERN = '~^NL\d{2}[A-Z]{4}\d{10}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'netherlands');
    }
}
