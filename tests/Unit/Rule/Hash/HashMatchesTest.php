<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Hash;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Hash\HashMatches;
use HarmonyIO\Validation\Rule\Rule;

class HashMatchesTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new HashMatches('1234567890'));
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new HashMatches('1234567890'))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new HashMatches('1234567890'))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new HashMatches('1234567890'))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new HashMatches('1234567890'))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new HashMatches('1234567890'))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new HashMatches('1234567890'))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new HashMatches('1234567890'))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new HashMatches('1234567890'))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenHashIsInvalid(): void
    {
        $this->assertFalse((new HashMatches('1234567890'))->validate('123456789'));
    }

    public function testValidateReturnsFalseWhenHashIsValid(): void
    {
        $this->assertTrue((new HashMatches('1234567890'))->validate('1234567890'));
    }
}
