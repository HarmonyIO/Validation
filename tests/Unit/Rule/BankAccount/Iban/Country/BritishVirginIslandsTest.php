<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\BritishVirginIslands;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class BritishVirginIslandsTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, BritishVirginIslands::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new BritishVirginIslands())->validate('XG96VPVG0000012345678901'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.BritishVirginIslands', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new BritishVirginIslands())->validate('VGx6VPVG0000012345678901'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.BritishVirginIslands', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new BritishVirginIslands())->validate('VG96vPVG0000012345678901'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.BritishVirginIslands', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new BritishVirginIslands())->validate('VG96VPVG000001234567890x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.BritishVirginIslands', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new BritishVirginIslands())->validate('VG96VPVG000001234567890'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.BritishVirginIslands', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new BritishVirginIslands())->validate('VG96VPVG00000123456789011'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.BritishVirginIslands', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new BritishVirginIslands())->validate('VG96VPVG0000012345678902'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new BritishVirginIslands())->validate('VG96VPVG0000012345678901'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
