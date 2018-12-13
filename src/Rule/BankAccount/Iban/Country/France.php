<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class France extends Country
{
    private const PATTERN = '~^FR\d{2}\d{5}\d{5}[a-zA-Z0-9]{11}\d{2}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'france');
    }
}
