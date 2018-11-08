<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Email;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Email\NativeEmailAddress;
use HarmonyIO\Validation\Rule\Rule;

class NativeEmailAddressTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new NativeEmailAddress());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new NativeEmailAddress())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new NativeEmailAddress())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new NativeEmailAddress())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new NativeEmailAddress())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new NativeEmailAddress())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new NativeEmailAddress())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new NativeEmailAddress())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenEmailAddressIsInvalid(): void
    {
        $this->assertFalse((new NativeEmailAddress())->validate('invalid-email-address'));
    }

    public function testValidateReturnsFalseWhenEmailAddressIsValid(): void
    {
        $this->assertTrue((new NativeEmailAddress())->validate('test@example.com'));
    }
}
