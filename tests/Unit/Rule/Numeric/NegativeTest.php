<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Numeric;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Numeric\Negative;
use HarmonyIO\Validation\Rule\Rule;

class NegativeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Negative());
    }

    public function testValidateReturnsTrueWhenPassingAnInteger(): void
    {
        $this->assertTrue((new Negative())->validate(-1));
    }

    public function testValidateReturnsTrueWhenPassingAFloat(): void
    {
        $this->assertTrue((new Negative())->validate(-1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Negative())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Negative())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Negative())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Negative())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Negative())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Negative())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerAsAString(): void
    {
        $this->assertTrue((new Negative())->validate('-1'));
    }

    public function testValidateReturnsTrueWhenPassingAFloatAsAString(): void
    {
        $this->assertTrue((new Negative())->validate('-1.1'));
    }

    public function testValidateReturnsFalseWhenPassingInZeroAsAString(): void
    {
        $this->assertFalse((new Negative())->validate('0'));
    }

    public function testValidateReturnsFalseWhenPassingInZeroAsAnInteger(): void
    {
        $this->assertFalse((new Negative())->validate(0));
    }

    public function testValidateReturnsFalseWhenPassingInZeroAsAFloat(): void
    {
        $this->assertFalse((new Negative())->validate(0.0));
    }

    public function testValidateReturnsFalseWhenPassingInAPositiveAsAString(): void
    {
        $this->assertFalse((new Negative())->validate('1'));
    }

    public function testValidateReturnsFalseWhenPassingInAPositiveAsAnInteger(): void
    {
        $this->assertFalse((new Negative())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingInAPositiveAsAFloat(): void
    {
        $this->assertFalse((new Negative())->validate(0.1));
    }
}
