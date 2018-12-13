<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Germany extends Country
{
    private const PATTERN = '~^DE\d{2}\d{8}\d{10}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'germany');
    }
}
