<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Email;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Email\RfcEmailAddress;
use HarmonyIO\Validation\Rule\Rule;

class RfcEmailAddressTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new RfcEmailAddress());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new RfcEmailAddress())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new RfcEmailAddress())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new RfcEmailAddress())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new RfcEmailAddress())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new RfcEmailAddress())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new RfcEmailAddress())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new RfcEmailAddress())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new RfcEmailAddress())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenEmailAddressIsInvalid(): void
    {
        $this->assertFalse((new RfcEmailAddress())->validate('invalid-email-address'));
    }

    public function testValidateReturnsFalseWhenEmailAddressIsValid(): void
    {
        $this->assertTrue((new RfcEmailAddress())->validate('test@example.com'));
    }
}
