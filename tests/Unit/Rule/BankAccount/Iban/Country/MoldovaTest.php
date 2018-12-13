<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Moldova;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class MoldovaTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Moldova::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Moldova())->validate('XD24AG000225100013104168'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Moldova', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Moldova())->validate('MDx4AG000225100013104168'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Moldova', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Moldova())->validate('MD24aG000225100013104168'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Moldova', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Moldova())->validate('MD24AG00022510001310416!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Moldova', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Moldova())->validate('MD24AG00022510001310416'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Moldova', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Moldova())->validate('MD24AG0002251000131041688'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Moldova', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Moldova())->validate('MD24AG000225100013104169'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Moldova())->validate('MD24AG000225100013104168'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
