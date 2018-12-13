<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Georgia;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class GeorgiaTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Georgia::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Georgia())->validate('XE29NB0000000101904917'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Georgia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Georgia())->validate('GEx9NB0000000101904917'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Georgia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Georgia())->validate('GE29nB0000000101904917'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Georgia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Georgia())->validate('GE29NB000000010190491x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Georgia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Georgia())->validate('GE29NB000000010190491'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Georgia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Georgia())->validate('GE29NB00000001019049177'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Georgia', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Georgia())->validate('GE29NB0000000101904918'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Georgia())->validate('GE29NB0000000101904917'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
