<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\FaroeIslands;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class FaroeIslandsTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, FaroeIslands::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new FaroeIslands())->validate('XO6264600001631634'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.FaroeIslands', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new FaroeIslands())->validate('FOx264600001631634'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.FaroeIslands', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new FaroeIslands())->validate('FO62x4600001631634'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.FaroeIslands', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new FaroeIslands())->validate('FO626460000163163x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.FaroeIslands', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new FaroeIslands())->validate('FO626460000163163'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.FaroeIslands', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new FaroeIslands())->validate('FO62646000016316344'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.FaroeIslands', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new FaroeIslands())->validate('FO6264600001631635'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new FaroeIslands())->validate('FO6264600001631634'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
