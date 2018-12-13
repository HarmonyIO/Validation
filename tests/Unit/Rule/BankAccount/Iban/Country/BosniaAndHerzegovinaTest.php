<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\BosniaAndHerzegovina;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class BosniaAndHerzegovinaTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, BosniaAndHerzegovina::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithCountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new BosniaAndHerzegovina())->validate('XA391290079401028494'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.BosniaAndHerzegovina', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveChecksum(): void
    {
        /** @var Result $result */
        $result = wait((new BosniaAndHerzegovina())->validate('BA291290079401028494'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.BosniaAndHerzegovina', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        /** @var Result $result */
        $result = wait((new BosniaAndHerzegovina())->validate('BA39x290079401028494'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.BosniaAndHerzegovina', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringDoesNotHaveAccountNumber(): void
    {
        /** @var Result $result */
        $result = wait((new BosniaAndHerzegovina())->validate('BA39129007940102849x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.BosniaAndHerzegovina', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new BosniaAndHerzegovina())->validate('BA39129007940102849'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.BosniaAndHerzegovina', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new BosniaAndHerzegovina())->validate('BA3912900794010284944'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Country.BosniaAndHerzegovina', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumFails(): void
    {
        /** @var Result $result */
        $result = wait((new BosniaAndHerzegovina())->validate('BA391290079401028495'));

        $this->assertFalse($result->isValid());
        $this->assertSame('BankAccount.Iban.Checksum', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsWhenPassingAValidIbanString(): void
    {
        /** @var Result $result */
        $result = wait((new BosniaAndHerzegovina())->validate('BA391290079401028494'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
