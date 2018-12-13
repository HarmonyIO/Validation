<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Austria;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class AustriaTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Austria::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Austria())->validate('XT611904300234573201'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Austria', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new Austria())->validate('ATx11904300234573201'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Austria', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new Austria())->validate('AT61x904300234573201'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Austria', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new Austria())->validate('AT61190430023457320x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Austria', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Austria())->validate('AT61190430023457320'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Austria', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Austria())->validate('AT6119043002345732011'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.Austria', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new Austria())->validate('AT611904300234573202'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new Austria())->validate('AT611904300234573201'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
