<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\SanMarino;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class SanMarinoTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, SanMarino::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new SanMarino())->validate('XM86U0322509800000000270100'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.SanMarino', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new SanMarino())->validate('SMx6U0322509800000000270100'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.SanMarino', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new SanMarino())->validate('SM86u0322509800000000270100'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.SanMarino', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new SanMarino())->validate('SM86U032250980000000027010!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.SanMarino', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new SanMarino())->validate('SM86U032250980000000027010'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.SanMarino', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new SanMarino())->validate('SM86U03225098000000002701000'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.SanMarino', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new SanMarino())->validate('SM86U0322509800000000270101'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new SanMarino())->validate('SM86U0322509800000000270100'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
