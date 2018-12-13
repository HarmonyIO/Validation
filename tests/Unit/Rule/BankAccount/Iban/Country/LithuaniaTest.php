<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Lithuania;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class LithuaniaTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Lithuania::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Lithuania())->validate('XT121000011101001000'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Lithuania', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Lithuania())->validate('LTx21000011101001000'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Lithuania', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Lithuania())->validate('LT12x000011101001000'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Lithuania', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Lithuania())->validate('LT12100001110100100x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Lithuania', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Lithuania())->validate('LT12100001110100100'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Lithuania', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Lithuania())->validate('LT1210000111010010000'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Lithuania', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Lithuania())->validate('LT121000011101001001'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Lithuania())->validate('LT121000011101001000'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
