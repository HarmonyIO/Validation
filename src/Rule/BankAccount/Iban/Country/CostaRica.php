<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class CostaRica extends Country
{
    private const PATTERN = '~^CR\d{2}0\d{3}\d{14}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'costaRica');
    }
}
