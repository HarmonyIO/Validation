<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Gibraltar;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class GibraltarTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Gibraltar::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Gibraltar())->validate('XI75NWBK000000007099453'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Gibraltar', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Gibraltar())->validate('GIx5NWBK000000007099453'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Gibraltar', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Gibraltar())->validate('GI75nWBK000000007099453'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Gibraltar', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Gibraltar())->validate('GI75NWBK00000000709945!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Gibraltar', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Gibraltar())->validate('GI75NWBK00000000709945'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Gibraltar', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Gibraltar())->validate('GI75NWBK0000000070994533'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Gibraltar', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Gibraltar())->validate('GI75NWBK000000007099454'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Gibraltar())->validate('GI75NWBK000000007099453'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
