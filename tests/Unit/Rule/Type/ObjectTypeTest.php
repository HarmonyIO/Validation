<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Type;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\ObjectType;

class ObjectTypeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new ObjectType());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new ObjectType())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new ObjectType())->validate(1.1));
    }

    public function testValidateReturnsTrueWhenPassingABoolean(): void
    {
        $this->assertFalse((new ObjectType())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new ObjectType())->validate([]));
    }

    public function testValidateReturnsTrueWhenPassingAnObject(): void
    {
        $this->assertTrue((new ObjectType())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new ObjectType())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new ObjectType())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsTrueWhenPassingACallable(): void
    {
        $this->assertTrue((new ObjectType())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenPassingAString(): void
    {
        $this->assertFalse((new ObjectType())->validate('€'));
    }
}
