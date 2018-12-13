<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Malta;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class MaltaTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Malta::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Malta())->validate('XT84MALT011000012345MTLCAST001S'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Malta', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Malta())->validate('MTx4MALT011000012345MTLCAST001S'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Malta', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Malta())->validate('MT84mALT011000012345MTLCAST001S'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Malta', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Malta())->validate('MT84MALT011000012345MTLCAST001!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Malta', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Malta())->validate('MT84MALT011000012345MTLCAST001'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Malta', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Malta())->validate('MT84MALT011000012345MTLCAST001SS'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Malta', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Malta())->validate('MT84MALT011000012345MTLCAST001T'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Malta())->validate('MT84MALT011000012345MTLCAST001S'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
