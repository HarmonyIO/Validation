<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Switzerland extends Country
{
    private const PATTERN = '~^CH\d{2}\d{5}\d{12}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'switzerland');
    }
}
