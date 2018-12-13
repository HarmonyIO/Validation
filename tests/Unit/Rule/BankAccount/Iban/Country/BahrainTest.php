<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Bahrain;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class BahrainTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Bahrain::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Bahrain())->validate('XH67BMAG00001299123456'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Bahrain', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Bahrain())->validate('BHx7BMAG00001299123456'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Bahrain', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Bahrain())->validate('BH671MAG00001299123456'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Bahrain', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Bahrain())->validate('BH67BMAG0000129912345!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Bahrain', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Bahrain())->validate('BH67BMAG0000129912345'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Bahrain', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Bahrain())->validate('BH67BMAG000012991234566'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Bahrain', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Bahrain())->validate('BH67BMAG00001299123457'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Bahrain())->validate('BH67BMAG00001299123456'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
