<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Portugal;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class PortugalTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Portugal::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Portugal())->validate('XT50000201231234567890154'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Portugal', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Portugal())->validate('PTx0000201231234567890154'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Portugal', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Portugal())->validate('PT50x00201231234567890154'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Portugal', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Portugal())->validate('PT5000020123123456789015x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Portugal', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Portugal())->validate('PT5000020123123456789015'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Portugal', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Portugal())->validate('PT500002012312345678901544'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Portugal', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Portugal())->validate('PT50000201231234567890155'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Portugal())->validate('PT50000201231234567890154'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
