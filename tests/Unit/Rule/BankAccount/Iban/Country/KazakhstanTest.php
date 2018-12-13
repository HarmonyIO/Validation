<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Kazakhstan;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class KazakhstanTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Kazakhstan::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Kazakhstan())->validate('XZ86125KZT5004100100'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Kazakhstan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Kazakhstan())->validate('KZx6125KZT5004100100'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Kazakhstan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Kazakhstan())->validate('KZ86x25KZT5004100100'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Kazakhstan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Kazakhstan())->validate('KZ86125KZT500410010!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Kazakhstan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Kazakhstan())->validate('KZ86125KZT500410010'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Kazakhstan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Kazakhstan())->validate('KZ86125KZT50041001000'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Kazakhstan', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Kazakhstan())->validate('KZ86125KZT5004100101'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Kazakhstan())->validate('KZ86125KZT5004100100'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
