<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Type;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\ResourceType;

class ResourceTypeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new ResourceType());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new ResourceType())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new ResourceType())->validate(1.1));
    }

    public function testValidateReturnsTrueWhenPassingABoolean(): void
    {
        $this->assertFalse((new ResourceType())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new ResourceType())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new ResourceType())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new ResourceType())->validate(null));
    }

    public function testValidateReturnsTrueWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertTrue((new ResourceType())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new ResourceType())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenPassingAString(): void
    {
        $this->assertFalse((new ResourceType())->validate('€'));
    }
}
