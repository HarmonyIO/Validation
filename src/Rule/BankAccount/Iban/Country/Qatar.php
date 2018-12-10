<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Qatar extends Country
{
    private const PATTERN = '~^QA\d{2}[A-Z]{4}[a-zA-Z0-9]{21}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'qatar');
    }
}
