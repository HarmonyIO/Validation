<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Estonia;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class EstoniaTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Estonia::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Estonia())->validate('XE382200221020145685'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Estonia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Estonia())->validate('EEx82200221020145685'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Estonia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Estonia())->validate('EE38x200221020145685'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Estonia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Estonia())->validate('EE38220022102014568x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Estonia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Estonia())->validate('EE38220022102014568'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Estonia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Estonia())->validate('EE3822002210201456855'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Estonia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Estonia())->validate('EE382200221020145686'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Estonia())->validate('EE382200221020145685'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
