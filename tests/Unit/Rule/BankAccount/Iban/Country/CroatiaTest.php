<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Croatia;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class CroatiaTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Croatia::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Croatia())->validate('XR1210010051863000160'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Croatia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Croatia())->validate('HRx210010051863000160'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Croatia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Croatia())->validate('HR12x0010051863000160'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Croatia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Croatia())->validate('HR121001005186300016x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Croatia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Croatia())->validate('HR121001005186300016'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Croatia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Croatia())->validate('HR12100100518630001600'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Croatia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Croatia())->validate('HR1210010051863000161'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateReturnsTrueWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Croatia())->validate('HR1210010051863000160'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
