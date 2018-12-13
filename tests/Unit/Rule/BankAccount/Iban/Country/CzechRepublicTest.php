<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\CzechRepublic;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class CzechRepublicTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, CzechRepublic::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new CzechRepublic())->validate('XZ6508000000192000145399'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.CzechRepublic', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new CzechRepublic())->validate('CZx508000000192000145399'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.CzechRepublic', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new CzechRepublic())->validate('CZ65x8000000192000145399'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.CzechRepublic', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new CzechRepublic())->validate('CZ650800000019200014539x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.CzechRepublic', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new CzechRepublic())->validate('CZ650800000019200014539'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.CzechRepublic', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new CzechRepublic())->validate('CZ65080000001920001453999'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.CzechRepublic', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new CzechRepublic())->validate('CZ6508000000192000145390'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new CzechRepublic())->validate('CZ6508000000192000145399'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
