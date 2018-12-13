<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Kosovo;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class KosovoTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Kosovo::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Kosovo())->validate('YK051212012345678906'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Kosovo', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Kosovo())->validate('XKx51212012345678906'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Kosovo', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Kosovo())->validate('XK05x212012345678906'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Kosovo', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Kosovo())->validate('XK05121201234567890!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Kosovo', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Kosovo())->validate('XK05121201234567890'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Kosovo', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Kosovo())->validate('XK0512120123456789066'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Kosovo', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Kosovo())->validate('XK051212012345678907'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Kosovo())->validate('XK051212012345678906'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
