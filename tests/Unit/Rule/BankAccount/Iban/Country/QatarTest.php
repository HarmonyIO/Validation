<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Qatar;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class QatarTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Qatar::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Qatar())->validate('XA58DOHB00001234567890ABCDEFG'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Qatar', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Qatar())->validate('QAx8DOHB00001234567890ABCDEFG'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Qatar', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Qatar())->validate('QA58dOHB00001234567890ABCDEFG'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Qatar', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Qatar())->validate('QA58DOHB00001234567890ABCDEF!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Qatar', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Qatar())->validate('QA58DOHB00001234567890ABCDEF'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Qatar', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Qatar())->validate('QA58DOHB00001234567890ABCDEFGG'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Qatar', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Qatar())->validate('QA58DOHB00001234567890ABCDEFH'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Qatar())->validate('QA58DOHB00001234567890ABCDEFG'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
