<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Greenland;
use HarmonyIO\Validation\Rule\Rule;

class GreenlandTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Greenland());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Greenland())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Greenland())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Greenland())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Greenland())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Greenland())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Greenland())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Greenland())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Greenland())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenStringDoesNotStartWithCountryCode(): void
    {
        $this->assertFalse((new Greenland())->validate('XL2000400440116243'));
    }

    public function testValidateReturnsFalseWhenStringDoesNotHaveChecksum(): void
    {
        $this->assertFalse((new Greenland())->validate('GLx000400440116243'));
    }

    public function testValidateReturnsFalseWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        $this->assertFalse((new Greenland())->validate('GL20x0400440116243'));
    }

    public function testValidateReturnsFalseWhenStringDoesNotHaveAccountNumber(): void
    {
        $this->assertFalse((new Greenland())->validate('GL200040044011624x'));
    }

    public function testValidateReturnsFalseWhenStringIsTooShort(): void
    {
        $this->assertFalse((new Greenland())->validate('GL200040044011624'));
    }

    public function testValidateReturnsFalseWhenStringIsTooLong(): void
    {
        $this->assertFalse((new Greenland())->validate('GL20004004401162433'));
    }

    public function testValidateReturnsTrueWhenPassingAValidIbanString(): void
    {
        $this->assertTrue((new Greenland())->validate('GL2000400440116243'));
    }
}
