<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class EastTimor extends Country
{
    private const PATTERN = '~^TL38\d{3}\d{16}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'eastTimor');
    }
}
