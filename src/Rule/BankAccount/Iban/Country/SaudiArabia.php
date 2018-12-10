<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class SaudiArabia extends Country
{
    private const PATTERN = '~^SA\d{2}\d{2}[a-zA-Z0-9]{18}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'saudiArabia');
    }
}
