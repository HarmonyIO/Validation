<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Albania;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class AlbaniaTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Albania::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Albania())->validate('XL47212110090000000235698741'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Albania', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Albania())->validate('ALx7212110090000000235698741'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Albania', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Albania())->validate('AL47x12110090000000235698741'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Albania', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Albania())->validate('AL4721211009000000023569874x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Albania', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Albania())->validate('AL4721211009000000023569874'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Albania', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Albania())->validate('AL472121100900000002356987411'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Albania', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Albania())->validate('AL47212110090000000235698742'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Albania())->validate('AL47212110090000000235698741'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
