<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Numeric;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Numeric\Positive;
use HarmonyIO\Validation\Rule\Rule;

class PositiveTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Positive());
    }

    public function testValidateReturnsTrueWhenPassingAnInteger(): void
    {
        $this->assertTrue((new Positive())->validate(1));
    }

    public function testValidateReturnsTrueWhenPassingAFloat(): void
    {
        $this->assertTrue((new Positive())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Positive())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Positive())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Positive())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Positive())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Positive())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Positive())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerAsAString(): void
    {
        $this->assertTrue((new Positive())->validate('1'));
    }

    public function testValidateReturnsTrueWhenPassingAFloatAsAString(): void
    {
        $this->assertTrue((new Positive())->validate('1.1'));
    }

    public function testValidateReturnsTrueWhenPassingInZeroAsAString(): void
    {
        $this->assertTrue((new Positive())->validate('0'));
    }

    public function testValidateReturnsTrueWhenPassingInZeroAsAnInteger(): void
    {
        $this->assertTrue((new Positive())->validate(0));
    }

    public function testValidateReturnsTrueWhenPassingInZeroAsAFloat(): void
    {
        $this->assertTrue((new Positive())->validate(0.0));
    }

    public function testValidateReturnsFalseWhenPassingInANegativeAsAString(): void
    {
        $this->assertFalse((new Positive())->validate('-1'));
    }

    public function testValidateReturnsFalseWhenPassingInANegativeAsAnInteger(): void
    {
        $this->assertFalse((new Positive())->validate(-1));
    }

    public function testValidateReturnsFalseWhenPassingInANegativeAsAFloat(): void
    {
        $this->assertFalse((new Positive())->validate(-0.1));
    }
}
