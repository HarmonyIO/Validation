<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Belgium;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class BelgiumTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Belgium::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Belgium())->validate('XE68539007547034'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Belgium', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Belgium())->validate('BEx8539007547034'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Belgium', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Belgium())->validate('BE68x39007547034'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Belgium', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Belgium())->validate('BE6853900754703x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Belgium', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Belgium())->validate('BE6853900754703'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Belgium', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Belgium())->validate('BE685390075470344'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Belgium', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Belgium())->validate('BE68539007547035'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Belgium())->validate('BE68539007547034'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
