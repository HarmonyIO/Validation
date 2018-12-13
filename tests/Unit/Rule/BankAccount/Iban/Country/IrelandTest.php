<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Ireland;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class IrelandTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Ireland::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Ireland())->validate('XE29AIBK93115212345678'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Ireland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Ireland())->validate('IEx9AIBK93115212345678'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Ireland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Ireland())->validate('IE29aIBK93115212345678'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Ireland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Ireland())->validate('IE29AIBK9311521234567x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Ireland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Ireland())->validate('IE29AIBK9311521234567'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Ireland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Ireland())->validate('IE29AIBK931152123456788'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Ireland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Ireland())->validate('IE29AIBK93115212345679'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Ireland())->validate('IE29AIBK93115212345678'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
