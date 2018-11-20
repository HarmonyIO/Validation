<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Bahrain;
use HarmonyIO\Validation\Rule\Rule;

class BahrainTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Bahrain());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Bahrain())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Bahrain())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Bahrain())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Bahrain())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Bahrain())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Bahrain())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Bahrain())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Bahrain())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenStringDoesNotStartWithCountryCode(): void
    {
        $this->assertFalse((new Bahrain())->validate('XH67BMAG00001299123456'));
    }

    public function testValidateReturnsFalseWhenStringDoesNotHaveChecksum(): void
    {
        $this->assertFalse((new Bahrain())->validate('BHx7BMAG00001299123456'));
    }

    public function testValidateReturnsFalseWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        $this->assertFalse((new Bahrain())->validate('BH67bMAG00001299123456'));
    }

    public function testValidateReturnsFalseWhenStringDoesNotHaveAccountNumber(): void
    {
        $this->assertFalse((new Bahrain())->validate('BH67BMAGx0001299123456'));
    }

    public function testValidateReturnsFalseWhenStringIsTooShort(): void
    {
        $this->assertFalse((new Bahrain())->validate('BH67BMAG0000129912345'));
    }

    public function testValidateReturnsFalseWhenStringIsTooLong(): void
    {
        $this->assertFalse((new Bahrain())->validate('BH67BMAG000012991234566'));
    }

    public function testValidateReturnsTrueWhenPassingAValidIbanString(): void
    {
        $this->assertTrue((new Bahrain())->validate('BH67BMAG00001299123456'));
    }
}
