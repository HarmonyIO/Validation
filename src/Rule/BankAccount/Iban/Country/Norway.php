<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Norway extends Country
{
    private const PATTERN = '~^NO\d{2}\d{4}\d{7}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'norway');
    }
}
