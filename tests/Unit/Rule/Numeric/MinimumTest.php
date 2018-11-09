<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Numeric;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Numeric\Minimum;
use HarmonyIO\Validation\Rule\Rule;

class MinimumTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Minimum(10));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerWhichIsLessThanMinimum(): void
    {
        $this->assertFalse((new Minimum(10))->validate(1));
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerWhichIsExactlyMinimum(): void
    {
        $this->assertTrue((new Minimum(10))->validate(10));
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerWhichIsLargerThanMinimum(): void
    {
        $this->assertTrue((new Minimum(10))->validate(11));
    }

    public function testValidateReturnsFalseWhenPassingAFloatWhichIsLessThanMinimum(): void
    {
        $this->assertFalse((new Minimum(10))->validate(1.1));
    }

    public function testValidateReturnsTrueWhenPassingAFloatWhichIsExactlyMinimum(): void
    {
        $this->assertTrue((new Minimum(10))->validate(10.0));
    }

    public function testValidateReturnsTrueWhenPassingAFloatWhichIsLargerThanMinimum(): void
    {
        $this->assertTrue((new Minimum(10))->validate(10.5));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Minimum(10))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Minimum(10))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Minimum(10))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Minimum(10))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Minimum(10))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Minimum(10))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerAsAStringWhichIsSmallerThanMinimum(): void
    {
        $this->assertFalse((new Minimum(10))->validate('1'));
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerAsAStringWhichIsExactlyThanMinimum(): void
    {
        $this->assertTrue((new Minimum(10))->validate('10'));
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerAsAStringWhichIsLargerThanMinimum(): void
    {
        $this->assertTrue((new Minimum(10))->validate('11'));
    }
}
