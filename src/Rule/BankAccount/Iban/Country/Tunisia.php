<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Tunisia extends Country
{
    private const PATTERN = '~^TN59\d{2}\d{3}\d{15}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'tunisia');
    }
}
