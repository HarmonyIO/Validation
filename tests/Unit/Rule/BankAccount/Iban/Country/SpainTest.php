<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Spain;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class SpainTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Spain::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Spain())->validate('XS9121000418450200051332'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Spain', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Spain())->validate('ESx121000418450200051332'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Spain', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Spain())->validate('ES91x1000418450200051332'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Spain', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Spain())->validate('ES912100041845020005133x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Spain', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Spain())->validate('ES912100041845020005133'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Spain', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Spain())->validate('ES91210004184502000513322'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Spain', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Spain())->validate('ES9121000418450200051333'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Spain())->validate('ES9121000418450200051332'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
