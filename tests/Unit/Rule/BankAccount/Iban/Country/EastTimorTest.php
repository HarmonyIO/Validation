<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\EastTimor;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class EastTimorTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, EastTimor::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new EastTimor())->validate('XL380080012345678910157'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.EastTimor', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new EastTimor())->validate('TL280080012345678910157'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.EastTimor', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new EastTimor())->validate('TL38x080012345678910157'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.EastTimor', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new EastTimor())->validate('TL38008001234567891015x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.EastTimor', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new EastTimor())->validate('TL38008001234567891015'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.EastTimor', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new EastTimor())->validate('TL3800800123456789101577'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.EastTimor', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new EastTimor())->validate('TL380080012345678910158'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new EastTimor())->validate('TL380080012345678910157'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
