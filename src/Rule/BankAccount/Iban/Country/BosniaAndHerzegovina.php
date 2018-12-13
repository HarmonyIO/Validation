<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class BosniaAndHerzegovina extends Country
{
    private const PATTERN = '~^BA39\d{3}\d{3}\d{10}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'bosniaAndHerzegovina');
    }
}
