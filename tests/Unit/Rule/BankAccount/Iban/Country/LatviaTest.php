<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Latvia;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class LatviaTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Latvia::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Latvia())->validate('XV80BANK0000435195001'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Latvia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Latvia())->validate('LVx0BANK0000435195001'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Latvia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Latvia())->validate('LV80bANK0000435195001'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Latvia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Latvia())->validate('LV80BANK000043519500!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Latvia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Latvia())->validate('LV80BANK000043519500'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Latvia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Latvia())->validate('LV80BANK00004351950011'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Latvia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Latvia())->validate('LV80BANK0000435195002'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Latvia())->validate('LV80BANK0000435195001'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
