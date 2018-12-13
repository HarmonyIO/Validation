<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Mauritius;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class MauritiusTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Mauritius::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Mauritius())->validate('XU17BOMM0101101030300200000MUR'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Mauritius', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Mauritius())->validate('MUx7BOMM0101101030300200000MUR'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Mauritius', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Mauritius())->validate('MU17bOMM0101101030300200000MUR'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Mauritius', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Mauritius())->validate('MU17BOMM0101101030300200000MU!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Mauritius', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Mauritius())->validate('MU17BOMM0101101030300200000MU'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Mauritius', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Mauritius())->validate('MU17BOMM0101101030300200000MURR'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Mauritius', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Mauritius())->validate('MU17BOMM0101101030300200000MUS'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Mauritius())->validate('MU17BOMM0101101030300200000MUR'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
