<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Liechtenstein extends Country
{
    private const PATTERN = '~^LI\d{2}\d{5}[a-zA-Z0-9]{12}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'liechtenstein');
    }
}
