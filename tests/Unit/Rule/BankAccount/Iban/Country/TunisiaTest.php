<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Tunisia;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class TunisiaTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Tunisia::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Tunisia())->validate('XN5910006035183598478831'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Tunisia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Tunisia())->validate('TN6910006035183598478831'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Tunisia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Tunisia())->validate('TN59x0006035183598478831'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Tunisia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Tunisia())->validate('TN591000603518359847883x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Tunisia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Tunisia())->validate('TN591000603518359847883'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Tunisia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Tunisia())->validate('TN59100060351835984788311'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Tunisia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Tunisia())->validate('TN5910006035183598478832'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Tunisia())->validate('TN5910006035183598478831'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
