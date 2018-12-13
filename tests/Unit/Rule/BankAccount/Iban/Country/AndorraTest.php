<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Andorra;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class AndorraTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Andorra::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Andorra())->validate('XD1200012030200359100100'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Andorra', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Andorra())->validate('ADx200012030200359100100'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Andorra', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Andorra())->validate('AD12x0012030200359100100'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Andorra', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Andorra())->validate('AD120001203020035910010x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Andorra', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Andorra())->validate('AD120001203020035910010'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Andorra', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Andorra())->validate('AD12000120302003591001000'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Andorra', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Andorra())->validate('AD12000120302003591001001'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Andorra', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Andorra())->validate('AD1200012030200359100100'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
