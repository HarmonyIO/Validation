<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Cyprus;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class CyprusTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Cyprus::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Cyprus())->validate('XY17002001280000001200527600'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Cyprus', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Cyprus())->validate('CYx7002001280000001200527600'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Cyprus', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Cyprus())->validate('CY17x02001280000001200527600'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Cyprus', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Cyprus())->validate('CY1700200128000000120052760!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Cyprus', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Cyprus())->validate('CY1700200128000000120052760'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Cyprus', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Cyprus())->validate('CY170020012800000012005276000'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Cyprus', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Cyprus())->validate('CY17002001280000001200527601'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Cyprus())->validate('CY17002001280000001200527600'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
