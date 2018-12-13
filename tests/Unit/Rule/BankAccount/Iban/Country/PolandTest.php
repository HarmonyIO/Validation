<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Poland;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class PolandTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Poland::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Poland())->validate('XL61109010140000071219812874'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Poland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Poland())->validate('PLx1109010140000071219812874'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Poland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Poland())->validate('PL61x09010140000071219812874'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Poland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Poland())->validate('PL6110901014000007121981287x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Poland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Poland())->validate('PL6110901014000007121981287'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Poland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Poland())->validate('PL611090101400000712198128744'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Poland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Poland())->validate('PL61109010140000071219812875'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Poland())->validate('PL61109010140000071219812874'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
