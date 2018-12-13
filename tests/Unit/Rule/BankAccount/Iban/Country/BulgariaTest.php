<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Bulgaria;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class BulgariaTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Bulgaria::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Bulgaria())->validate('XG80BNBG96611020345678'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Bulgaria', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Bulgaria())->validate('BGx0BNBG96611020345678'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Bulgaria', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Bulgaria())->validate('BG80bNBG96611020345678'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Bulgaria', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Bulgaria())->validate('BG80BNBG9661102034567!'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Bulgaria', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Bulgaria())->validate('BG80BNBG9661102034567'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Bulgaria', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Bulgaria())->validate('BG80BNBG966110203456788'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Bulgaria', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Bulgaria())->validate('BG80BNBG96611020345679'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Bulgaria())->validate('BG80BNBG96611020345678'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
