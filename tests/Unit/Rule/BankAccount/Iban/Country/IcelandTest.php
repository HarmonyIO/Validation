<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Iceland;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class IcelandTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Iceland::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Iceland())->validate('XS140159260076545510730339'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Iceland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Iceland())->validate('ISx40159260076545510730339'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Iceland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Iceland())->validate('IS14x159260076545510730339'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Iceland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Iceland())->validate('IS14015926007654551073033x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Iceland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Iceland())->validate('IS14015926007654551073033'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Iceland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Iceland())->validate('IS1401592600765455107303399'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Iceland', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Iceland())->validate('IS140159260076545510730330'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Iceland())->validate('IS140159260076545510730339'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
