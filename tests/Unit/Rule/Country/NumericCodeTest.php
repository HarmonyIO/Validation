<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Country;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Country\NumericCode;
use HarmonyIO\Validation\Rule\Rule;

class NumericCodeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new NumericCode());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new NumericCode())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new NumericCode())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new NumericCode())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new NumericCode())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new NumericCode())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new NumericCode())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new NumericCode())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new NumericCode())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsTrueWhenPassingAValidAlpha2CountryCode(): void
    {
        $this->assertTrue((new NumericCode())->validate('528'));
    }

    public function testValidateReturnsFalseWhenAnInvalidAlpha2CountryCode(): void
    {
        $this->assertFalse((new NumericCode())->validate('529'));
    }
}
