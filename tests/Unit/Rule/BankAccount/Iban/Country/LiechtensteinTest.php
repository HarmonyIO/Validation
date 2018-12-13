<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Liechtenstein;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class LiechtensteinTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Liechtenstein::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Liechtenstein())->validate('XI21088100002324013AA'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Liechtenstein', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Liechtenstein())->validate('LIx1088100002324013AA'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Liechtenstein', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Liechtenstein())->validate('LI21x88100002324013AA'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Liechtenstein', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Liechtenstein())->validate('LI21088100002324013A!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Liechtenstein', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Liechtenstein())->validate('LI21088100002324013A'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Liechtenstein', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Liechtenstein())->validate('LI21088100002324013AAA'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Liechtenstein', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Liechtenstein())->validate('LI21088100002324013AB'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Liechtenstein())->validate('LI21088100002324013AA'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
