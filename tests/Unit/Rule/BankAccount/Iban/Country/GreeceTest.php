<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Greece;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class GreeceTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Greece::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Greece())->validate('XR1601101250000000012300695'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Greece', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Greece())->validate('GRx601101250000000012300695'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Greece', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Greece())->validate('GR16x1101250000000012300695'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Greece', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Greece())->validate('GR160110125000000001230069!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Greece', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Greece())->validate('GR160110125000000001230069'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Greece', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Greece())->validate('GR16011012500000000123006955'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Greece', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Greece())->validate('GR1601101250000000012300696'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Greece())->validate('GR1601101250000000012300695'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
