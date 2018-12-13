<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\DominicanRepublic;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class DominicanRepublicTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, DominicanRepublic::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new DominicanRepublic())->validate('XO28BAGR00000001212453611324'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.DominicanRepublic', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new DominicanRepublic())->validate('DOx8BAGR00000001212453611324'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.DominicanRepublic', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new DominicanRepublic())->validate('DO28bAGR00000001212453611324'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.DominicanRepublic', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new DominicanRepublic())->validate('DO28BAGR0000000121245361132x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.DominicanRepublic', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new DominicanRepublic())->validate('DO28BAGR0000000121245361132'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.DominicanRepublic', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new DominicanRepublic())->validate('DO28BAGR000000012124536113244'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.DominicanRepublic', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new DominicanRepublic())->validate('DO28BAGR00000001212453611325'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new DominicanRepublic())->validate('DO28BAGR00000001212453611324'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
