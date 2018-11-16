<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\DataFormat;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\DataFormat\Json;
use HarmonyIO\Validation\Rule\Rule;

class JsonTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Json());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Json())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Json())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Json())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Json())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Json())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Json())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Json())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Json())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsTrueWhenPassingAValidJsonString(): void
    {
        $this->assertTrue((new Json())->validate('{"foo": "bar"}'));
    }

    public function testValidateReturnsFalseWhenPassingAnInvalidJsonString(): void
    {
        $this->assertFalse((new Json())->validate('{foo: "bar"}'));
    }
}
