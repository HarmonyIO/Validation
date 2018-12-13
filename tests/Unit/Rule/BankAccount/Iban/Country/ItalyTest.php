<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Italy;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class ItalyTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Italy::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Italy())->validate('XT60X0542811101000000123456'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Italy', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Italy())->validate('ITx0X0542811101000000123456'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Italy', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Italy())->validate('IT60x0542811101000000123456'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Italy', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Italy())->validate('IT60X054281110100000012345!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Italy', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Italy())->validate('IT60X054281110100000012345'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Italy', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Italy())->validate('IT60X05428111010000001234566'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Italy', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Italy())->validate('IT60X0542811101000000123457'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Italy())->validate('IT60X0542811101000000123456'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
