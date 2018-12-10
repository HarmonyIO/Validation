<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Sweden extends Country
{
    private const PATTERN = '~^SE\d{2}\d{3}\d{17}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'sweden');
    }
}
