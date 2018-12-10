<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Albania extends Country
{
    private const PATTERN = '~^AL\d{2}\d{8}[a–zA-Z0-9]{16}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'albania');
    }
}
