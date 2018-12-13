<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\CostaRica;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class CostaRicaTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, CostaRica::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new CostaRica())->validate('XR05015202001026284066'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.CostaRica', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new CostaRica())->validate('CRx5015202001026284066'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.CostaRica', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveReserveNumber(): void
    {
        /** @var Result $result */
        $result = wait((new CostaRica())->validate('CR05115202001026284066'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.CostaRica', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new CostaRica())->validate('CR050x5202001026284066'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.CostaRica', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new CostaRica())->validate('CR0501520200102628406x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.CostaRica', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new CostaRica())->validate('CR0501520200102628406'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.CostaRica', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new CostaRica())->validate('CR050152020010262840666'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.CostaRica', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new CostaRica())->validate('CR05015202001026284067'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new CostaRica())->validate('CR05015202001026284066'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
