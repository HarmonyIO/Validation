<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Sweden;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class SwedenTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Sweden::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Sweden())->validate('XE4550000000058398257466'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Sweden', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Sweden())->validate('SEx550000000058398257466'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Sweden', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Sweden())->validate('SE45x0000000058398257466'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Sweden', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Sweden())->validate('SE455000000005839825746x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Sweden', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Sweden())->validate('SE455000000005839825746'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Sweden', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Sweden())->validate('SE45500000000583982574666'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Sweden', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Sweden())->validate('SE4550000000058398257467'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Sweden())->validate('SE4550000000058398257466'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
