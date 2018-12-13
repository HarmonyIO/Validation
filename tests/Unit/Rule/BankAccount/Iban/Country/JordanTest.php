<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Jordan;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class JordanTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Jordan::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Jordan())->validate('XO94CBJO0010000000000131000302'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Jordan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Jordan())->validate('JOx4CBJO0010000000000131000302'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Jordan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Jordan())->validate('JO94cBJO0010000000000131000302'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Jordan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Jordan())->validate('JO94CBJO001000000000013100030!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Jordan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Jordan())->validate('JO94CBJO001000000000013100030'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Jordan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Jordan())->validate('JO94CBJO00100000000001310003022'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Jordan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Jordan())->validate('JO94CBJO0010000000000131000303'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Jordan())->validate('JO94CBJO0010000000000131000302'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
