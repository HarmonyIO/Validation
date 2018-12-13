<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Malta extends Country
{
    private const PATTERN = '~^MT\d{2}[A-Z]{4}\d{5}[a-zA-Z0-9]{18}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'malta');
    }
}
