<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\France;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class FranceTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, France::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new France())->validate('XR1420041010050500013M02606'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.France', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new France())->validate('FRx420041010050500013M02606'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.France', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new France())->validate('FR14x0041010050500013M02606'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.France', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new France())->validate('FR1420041010050500013M0260x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.France', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new France())->validate('FR1420041010050500013M0260'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.France', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new France())->validate('FR1420041010050500013M026066'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.France', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new France())->validate('FR1420041010050500013M02607'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new France())->validate('FR1420041010050500013M02606'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
