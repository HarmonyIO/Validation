<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Spain extends Country
{
    private const PATTERN = '~^ES\d{2}\d{4}\d{4}\d{2}\d{10}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'spain');
    }
}
