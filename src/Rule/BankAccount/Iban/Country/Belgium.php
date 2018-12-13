<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Belgium extends Country
{
    private const PATTERN = '~^BE\d{2}\d{3}\d{7}\d{2}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'belgium');
    }
}
