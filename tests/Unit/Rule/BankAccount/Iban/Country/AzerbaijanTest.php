<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Azerbaijan;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class AzerbaijanTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Azerbaijan::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Azerbaijan())->validate('XZ21NABZ00000000137010001944'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Azerbaijan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Azerbaijan())->validate('AZx1NABZ00000000137010001944'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Azerbaijan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Azerbaijan())->validate('AZ211ABZ00000000137010001944'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Azerbaijan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Azerbaijan())->validate('AZ21NABZ0000000013701000194!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Azerbaijan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Azerbaijan())->validate('AZ21NABZ0000000013701000194'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Azerbaijan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Azerbaijan())->validate('AZ21NABZ000000001370100019444'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Azerbaijan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Azerbaijan())->validate('AZ21NABZ00000000137010001945'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Azerbaijan())->validate('AZ21NABZ00000000137010001944'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
