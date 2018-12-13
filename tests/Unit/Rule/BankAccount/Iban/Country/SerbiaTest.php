<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Serbia;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class SerbiaTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Serbia::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Serbia())->validate('XS35260005601001611379'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Serbia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Serbia())->validate('RS45260005601001611379'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Serbia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Serbia())->validate('RS35x60005601001611379'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Serbia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Serbia())->validate('RS3526000560100161137x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Serbia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Serbia())->validate('RS3526000560100161137'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Serbia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Serbia())->validate('RS352600056010016113799'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Serbia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Serbia())->validate('RS35260005601001611370'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Serbia())->validate('RS35260005601001611379'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
