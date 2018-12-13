<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Hungary;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class HungaryTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Hungary::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Hungary())->validate('XU42117730161111101800000000'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Hungary', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Hungary())->validate('HUx2117730161111101800000000'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Hungary', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Hungary())->validate('HU42x17730161111101800000000'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Hungary', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Hungary())->validate('HU4211773016111110180000000x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Hungary', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Hungary())->validate('HU4211773016111110180000000'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Hungary', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Hungary())->validate('HU421177301611111018000000000'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Hungary', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Hungary())->validate('HU42117730161111101800000001'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Hungary())->validate('HU42117730161111101800000000'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
