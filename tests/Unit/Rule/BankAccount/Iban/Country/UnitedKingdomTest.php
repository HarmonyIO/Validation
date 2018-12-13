<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\UnitedKingdom;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class UnitedKingdomTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, UnitedKingdom::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new UnitedKingdom())->validate('XB29NWBK60161331926819'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.UnitedKingdom', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new UnitedKingdom())->validate('GBx9NWBK60161331926819'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.UnitedKingdom', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new UnitedKingdom())->validate('GB29nWBK60161331926819'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.UnitedKingdom', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new UnitedKingdom())->validate('GB29NWBK6016133192681x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.UnitedKingdom', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new UnitedKingdom())->validate('GB29NWBK6016133192681'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.UnitedKingdom', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new UnitedKingdom())->validate('GB29NWBK601613319268199'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.UnitedKingdom', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new UnitedKingdom())->validate('GB29NWBK60161331926810'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new UnitedKingdom())->validate('GB29NWBK60161331926819'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
