<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Pakistan;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class PakistanTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Pakistan::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Pakistan())->validate('XK36SCBL0000001123456702'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Pakistan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Pakistan())->validate('PKx6SCBL0000001123456702'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Pakistan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Pakistan())->validate('PK36sCBL0000001123456702'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Pakistan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Pakistan())->validate('PK36SCBL000000112345670!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Pakistan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Pakistan())->validate('PK36SCBL000000112345670'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Pakistan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Pakistan())->validate('PK36SCBL00000011234567022'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Pakistan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Pakistan())->validate('PK36SCBL0000001123456703'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Pakistan())->validate('PK36SCBL0000001123456702'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
