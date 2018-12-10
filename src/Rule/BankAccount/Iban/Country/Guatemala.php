<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Guatemala extends Country
{
    private const PATTERN = '~^GT\d{2}[A-Z]{4}\d{2}\d{2}[a-zA-Z0-9]{16}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'guatemala');
    }
}
