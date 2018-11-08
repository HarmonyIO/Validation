<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Type;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\NullType;

class NullTypeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new NullType());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new NullType())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new NullType())->validate(1.1));
    }

    public function testValidateReturnsTrueWhenPassingABoolean(): void
    {
        $this->assertFalse((new NullType())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new NullType())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new NullType())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsTrueWhenPassingNull(): void
    {
        $this->assertTrue((new NullType())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new NullType())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new NullType())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenPassingAString(): void
    {
        $this->assertFalse((new NullType())->validate('€'));
    }
}
