<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Finland;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class FinlandTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Finland::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Finland())->validate('XI2112345600000785'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Finland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Finland())->validate('FIx112345600000785'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Finland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Finland())->validate('FI21x2345600000785'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Finland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Finland())->validate('FI211234560000078x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Finland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Finland())->validate('FI211234560000078'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Finland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Finland())->validate('FI21123456000007855'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Finland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Finland())->validate('FI2112345600000786'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Finland())->validate('FI2112345600000785'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
