<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Denmark;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class DenmarkTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Denmark::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Denmark())->validate('XK5000400440116243'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Denmark', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Denmark())->validate('DKx000400440116243'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Denmark', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Denmark())->validate('DK50x0400440116243'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Denmark', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Denmark())->validate('DK500040044011624x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Denmark', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Denmark())->validate('DK500040044011624'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Denmark', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Denmark())->validate('DK50004004401162433'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Denmark', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Denmark())->validate('DK5000400440116244'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Denmark())->validate('DK5000400440116243'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
