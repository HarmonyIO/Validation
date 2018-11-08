<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Type;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\IntegerType;

class IntegerTypeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new IntegerType());
    }

    public function testValidateReturnsTrueWhenPassingAnInteger(): void
    {
        $this->assertTrue((new IntegerType())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new IntegerType())->validate(1.1));
    }

    public function testValidateReturnsTrueWhenPassingABoolean(): void
    {
        $this->assertFalse((new IntegerType())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new IntegerType())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new IntegerType())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new IntegerType())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new IntegerType())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new IntegerType())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenPassingAString(): void
    {
        $this->assertFalse((new IntegerType())->validate('€'));
    }
}
