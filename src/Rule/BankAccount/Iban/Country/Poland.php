<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Poland extends Country
{
    private const PATTERN = '~^PL\d{2}\d{8}\d{16}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'poland');
    }
}
