<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Numeric;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Numeric\IsFloat;
use HarmonyIO\Validation\Rule\Rule;

class IsFloatTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new IsFloat());
    }

    public function testValidateReturnsTrueWhenPassingAnInteger(): void
    {
        $this->assertTrue((new IsFloat())->validate(1));
    }

    public function testValidateReturnsTrueWhenPassingAFloat(): void
    {
        $this->assertTrue((new IsFloat())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new IsFloat())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new IsFloat())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new IsFloat())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new IsFloat())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new IsFloat())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerAsAString(): void
    {
        $this->assertTrue((new IsFloat())->validate('1'));
    }

    public function testValidateReturnsTrueWhenPassingAFloatAsAString(): void
    {
        $this->assertTrue((new IsFloat())->validate('1.1'));
    }
}
