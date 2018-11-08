<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Type;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Exception\InvalidFullyQualifiedClassOrInterfaceName;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\InstanceOfType;

class InstanceofTypeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new InstanceOfType(\DateTimeImmutable::class));
    }

    public function testConstructorThrowsWhenPassingAnInvalidFullyQualifiedClassName(): void
    {
        $this->expectException(InvalidFullyQualifiedClassOrInterfaceName::class);

        new InstanceOfType('Foo\\Bar');
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new InstanceOfType(\DateTimeImmutable::class))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new InstanceOfType(\DateTimeImmutable::class))->validate(1.1));
    }

    public function testValidateReturnsTrueWhenPassingABoolean(): void
    {
        $this->assertFalse((new InstanceOfType(\DateTimeImmutable::class))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new InstanceOfType(\DateTimeImmutable::class))->validate([]));
    }

    public function testValidateReturnsTrueWhenPassingAnObjectMatchedAgainstSelf(): void
    {
        $this->assertTrue((new InstanceOfType(\DateTimeImmutable::class))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsTrueWhenPassingAnObjectMatchedAgainstParent(): void
    {
        $this->assertTrue(
            (new InstanceOfType(\ReflectionFunctionAbstract::class))->validate(new \ReflectionFunction('strlen'))
        );
    }

    public function testValidateReturnsTrueWhenPassingAnObjectMatchedAgainstInterface(): void
    {
        $this->assertTrue((new InstanceOfType(\DateTimeInterface::class))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new InstanceOfType(\DateTimeImmutable::class))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new InstanceOfType(\DateTimeImmutable::class))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new InstanceOfType(\DateTimeImmutable::class))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenPassingAString(): void
    {
        $this->assertFalse((new InstanceOfType(\DateTimeImmutable::class))->validate('€'));
    }
}
