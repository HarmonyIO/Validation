<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Text;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Exception\InvalidNumericalRange;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Text\LengthRange;

class LengthRangeTest extends TestCase
{
    public function testConstructorThrowsUpWhenMinimumLengthIsGreaterThanTheMaximumLength(): void
    {
        $this->expectException(InvalidNumericalRange::class);
        $this->expectExceptionMessage('The minimum (`12`) can not be greater than the maximum (`10`).');

        new LengthRange(12, 10);
    }

    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new LengthRange(10, 12));
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new LengthRange(10, 12))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new LengthRange(10, 12))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new LengthRange(10, 12))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new LengthRange(10, 12))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new LengthRange(10, 12))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new LengthRange(10, 12))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new LengthRange(10, 12))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new LengthRange(10, 12))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenPassingAStringSmallerThanTheMinimumLength(): void
    {
        $this->assertFalse((new LengthRange(10, 12))->validate('€€€€€€€€€'));
    }

    public function testValidateReturnsFalseWhenPassingAStringLargerThanTheMaximumLength(): void
    {
        $this->assertFalse((new LengthRange(10, 12))->validate('€€€€€€€€€€€€€'));
    }

    public function testValidateReturnsTrueWhenPassingAStringLargerThanTheMinimumLengthAndSmallerThanMaximumLength(): void
    {
        $this->assertTrue((new LengthRange(10, 12))->validate('€€€€€€€€€€€'));
    }

    public function testValidateReturnsTrueWhenPassingAStringWithExactlyTheMinimumLength(): void
    {
        $this->assertTrue((new LengthRange(10, 12))->validate('€€€€€€€€€€'));
    }

    public function testValidateReturnsTrueWhenPassingAStringExactlyTheMaximumLength(): void
    {
        $this->assertTrue((new LengthRange(10, 12))->validate('€€€€€€€€€€€€'));
    }
}
