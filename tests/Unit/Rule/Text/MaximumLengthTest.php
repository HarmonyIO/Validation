<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Text;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Text\MaximumLength;

class MaximumLengthTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new MaximumLength(10));
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new MaximumLength(10))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new MaximumLength(10))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new MaximumLength(10))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new MaximumLength(10))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new MaximumLength(10))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new MaximumLength(10))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new MaximumLength(10))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new MaximumLength(10))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsTrueWhenPassingAStringSmallerThanTheMaximumLength(): void
    {
        $this->assertTrue((new MaximumLength(10))->validate('€€€€€€€€€'));
    }

    public function testValidateReturnsTrueWhenPassingAStringWithExactlyTheMaximumLength(): void
    {
        $this->assertTrue((new MaximumLength(10))->validate('€€€€€€€€€€'));
    }

    public function testValidateReturnsFalseWhenPassingAStringLongerThanTheMaximumLength(): void
    {
        $this->assertFalse((new MaximumLength(10))->validate('€€€€€€€€€€€'));
    }
}
