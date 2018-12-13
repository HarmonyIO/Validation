<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Brazil;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class BrazilTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Brazil::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Brazil())->validate('XR9700360305000010009795493P1'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Brazil', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Brazil())->validate('BRx700360305000010009795493P1'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Brazil', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Brazil())->validate('BR97x0360305000010009795493P1'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Brazil', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Brazil())->validate('BR970036030500x010009795493P1'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Brazil', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAnAccountType(): void
    {
        /** @var Result $result */
        $result = wait((new Brazil())->validate('BR970036030500001000979549311'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Brazil', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAndAccountHolderPosition(): void
    {
        /** @var Result $result */
        $result = wait((new Brazil())->validate('BR9700360305000010009795493P!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Brazil', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Brazil())->validate('BR970036030500001000979593P1'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Brazil', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Brazil())->validate('BR9700360305000010009795493P11'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Brazil', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Brazil())->validate('BR9700360305000010009795493P2'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Brazil())->validate('BR9700360305000010009795493P1'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
