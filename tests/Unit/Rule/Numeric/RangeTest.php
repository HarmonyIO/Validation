<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Numeric;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Exception\InvalidNumericalRange;
use HarmonyIO\Validation\Exception\InvalidNumericValue;
use HarmonyIO\Validation\Rule\Numeric\Range;
use HarmonyIO\Validation\Rule\Rule;

class RangeTest extends TestCase
{
    public function testConstructorThrowsOnNonNumericMinimumValue(): void
    {
        $this->expectException(InvalidNumericValue::class);
        $this->expectExceptionMessage('Value (`one`) must be a numeric value.');

        new Range('one', 2);
    }

    public function testConstructorThrowsOnNonNumericMaximumValue(): void
    {
        $this->expectException(InvalidNumericValue::class);
        $this->expectExceptionMessage('Value (`two`) must be a numeric value.');

        new Range(1, 'two');
    }

    public function testConstructorThrowsWhenMinimumValueIsGreaterThanMaximumValue(): void
    {
        $this->expectException(InvalidNumericalRange::class);
        $this->expectExceptionMessage('The minimum (`51`) can not be greater than the maximum (`50`).');

        new Range(51, 50);
    }

    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Range(13, 16));
    }

    public function testValidateReturnsTrueWhenPassingAnInteger(): void
    {
        $this->assertTrue((new Range(13, 16))->validate(14));
    }

    public function testValidateReturnsTrueWhenPassingAFloat(): void
    {
        $this->assertTrue((new Range(13, 16))->validate(14.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Range(13, 16))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Range(13, 16))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Range(13, 16))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Range(13, 16))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Range(13, 16))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Range(13, 16))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerAsAString(): void
    {
        $this->assertTrue((new Range(13, 16))->validate('14'));
    }

    public function testValidateReturnsTrueWhenPassingAFloatAsAString(): void
    {
        $this->assertTrue((new Range(13, 16))->validate('14.1'));
    }

    public function testValidateReturnsFalseWhenPassingAFloatValueLessThanTheMinimumValue(): void
    {
        $this->assertFalse((new Range(13, 16))->validate(12.9));
    }

    public function testValidateReturnsFalseWhenPassingAFloatValueMoreThanTheMaximumValue(): void
    {
        $this->assertFalse((new Range(13, 16))->validate(16.1));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerValueLessThanTheMinimumValue(): void
    {
        $this->assertFalse((new Range(13, 16))->validate(12));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerValueMoreThanTheMaximumValue(): void
    {
        $this->assertFalse((new Range(13, 16))->validate(17));
    }
}
