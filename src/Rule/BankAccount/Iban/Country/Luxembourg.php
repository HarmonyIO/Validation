<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Luxembourg extends Country
{
    private const PATTERN = '~^LU\d{2}\d{3}[a-zA-Z0-9]{13}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'luxembourg');
    }
}
