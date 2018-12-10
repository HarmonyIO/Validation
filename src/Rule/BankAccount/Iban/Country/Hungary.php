<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Hungary extends Country
{
    private const PATTERN = '~^HU\d{2}\d{3}\d{5}\d{16}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'hungary');
    }
}
