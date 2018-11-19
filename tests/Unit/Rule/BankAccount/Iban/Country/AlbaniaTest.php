<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Albania;
use HarmonyIO\Validation\Rule\Rule;

class AlbaniaTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Albania());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Albania())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Albania())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Albania())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Albania())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Albania())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Albania())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Albania())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Albania())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenStringDoesNotStartWithCountryCode(): void
    {
        $this->assertFalse((new Albania())->validate('XL901490122010010999234354354'));
    }

    public function testValidateReturnsFalseWhenStringDoesNotHaveChecksum(): void
    {
        $this->assertFalse((new Albania())->validate('ALx01490122010010999234354354'));
    }

    public function testValidateReturnsFalseWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        $this->assertFalse((new Albania())->validate('AL90x490122010010999234354354'));
    }

    public function testValidateReturnsFalseWhenStringDoesNotHaveAccountNumber(): void
    {
        $this->assertFalse((new Albania())->validate('AL90149012201001099923435435x'));
    }

    public function testValidateReturnsFalseWhenStringIsTooShort(): void
    {
        $this->assertFalse((new Albania())->validate('AL90149012201001099923435435'));
    }

    public function testValidateReturnsFalseWhenStringIsTooLong(): void
    {
        $this->assertFalse((new Albania())->validate('AL901490122010010999234354355'));
    }

    public function testValidateReturnsTrueWhenPassingAValidIbanString(): void
    {
        $this->assertTrue((new Albania())->validate('AL47212110090000000235698741'));
    }
}
