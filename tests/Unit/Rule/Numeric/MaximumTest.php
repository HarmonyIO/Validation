<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Numeric;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Numeric\Maximum;
use HarmonyIO\Validation\Rule\Rule;

class MaximumTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Maximum(10));
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerWhichIsLessThanMaximum(): void
    {
        $this->assertTrue((new Maximum(10))->validate(1));
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerWhichIsExactlyMaximum(): void
    {
        $this->assertTrue((new Maximum(10))->validate(10));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerWhichIsLargerThanMaximum(): void
    {
        $this->assertFalse((new Maximum(10))->validate(11));
    }

    public function testValidateReturnsTrueWhenPassingAFloatWhichIsLessThanMaximum(): void
    {
        $this->assertTrue((new Maximum(10))->validate(1.1));
    }

    public function testValidateReturnsTrueWhenPassingAFloatWhichIsExactlyMaximum(): void
    {
        $this->assertTrue((new Maximum(10))->validate(10.0));
    }

    public function testValidateReturnsFalseWhenPassingAFloatWhichIsLargerThanMaximum(): void
    {
        $this->assertFalse((new Maximum(10))->validate(10.5));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Maximum(10))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Maximum(10))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Maximum(10))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Maximum(10))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Maximum(10))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Maximum(10))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerAsAStringWhichIsSmallerThanMaximum(): void
    {
        $this->assertTrue((new Maximum(10))->validate('1'));
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerAsAStringWhichIsExactlyThanMaximum(): void
    {
        $this->assertTrue((new Maximum(10))->validate('10'));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerAsAStringWhichIsLargerThanMaximum(): void
    {
        $this->assertFalse((new Maximum(10))->validate('11'));
    }
}
