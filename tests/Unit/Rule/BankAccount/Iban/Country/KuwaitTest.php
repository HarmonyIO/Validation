<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Kuwait;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class KuwaitTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Kuwait::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Kuwait())->validate('XW81CBKU0000000000001234560101'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Kuwait', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Kuwait())->validate('KWx1CBKU0000000000001234560101'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Kuwait', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Kuwait())->validate('KW81cBKU0000000000001234560101'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Kuwait', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Kuwait())->validate('KW81CBKU000000000000123456010!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Kuwait', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Kuwait())->validate('KW81CBKU000000000000123456010'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Kuwait', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Kuwait())->validate('KW81CBKU00000000000012345601011'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Kuwait', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Kuwait())->validate('KW81CBKU0000000000001234560102'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Kuwait())->validate('KW81CBKU0000000000001234560101'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
