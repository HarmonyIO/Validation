<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Macedonia;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class MacedoniaTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Macedonia::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Macedonia())->validate('XK07250120000058984'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Macedonia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Macedonia())->validate('MK17250120000058984'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Macedonia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Macedonia())->validate('MK07x50120000058984'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Macedonia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Macedonia())->validate('MK0725012000005898!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Macedonia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Macedonia())->validate('MK0725012000005898'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Macedonia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Macedonia())->validate('MK072501200000589844'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Macedonia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Macedonia())->validate('MK07250120000058985'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Macedonia())->validate('MK07250120000058984'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
