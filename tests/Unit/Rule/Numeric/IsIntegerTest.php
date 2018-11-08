<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Numeric;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Numeric\IsInteger;
use HarmonyIO\Validation\Rule\Rule;

class IsIntegerTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new IsInteger());
    }

    public function testValidateReturnsTrueWhenPassingAnInteger(): void
    {
        $this->assertTrue((new IsInteger())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new IsInteger())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new IsInteger())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new IsInteger())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new IsInteger())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new IsInteger())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new IsInteger())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new IsInteger())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerAsAString(): void
    {
        $this->assertTrue((new IsInteger())->validate('1'));
    }

    public function testValidateReturnsFalseWhenPassingAFloatAsAString(): void
    {
        $this->assertFalse((new IsInteger())->validate('1.1'));
    }
}
