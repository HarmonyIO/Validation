<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Netherlands;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class NetherlandsTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Netherlands::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Netherlands())->validate('XL91ABNA0417164300'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Netherlands', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Netherlands())->validate('NLx1ABNA0417164300'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Netherlands', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Netherlands())->validate('NL91aBNA0417164300'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Netherlands', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Netherlands())->validate('NL91ABNA041716430x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Netherlands', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Netherlands())->validate('NL91ABNA041716430'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Netherlands', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Netherlands())->validate('NL91ABNA04171643000'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Netherlands', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Netherlands())->validate('NL91ABNA0417164301'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Netherlands())->validate('NL91ABNA0417164300'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
