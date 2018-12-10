<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Lithuania extends Country
{
    private const PATTERN = '~^LT\d{2}\d{5}\d{11}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'lithuania');
    }
}
