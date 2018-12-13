<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Montenegro extends Country
{
    private const PATTERN = '~^ME25\d{3}\d{15}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'montenegro');
    }
}
