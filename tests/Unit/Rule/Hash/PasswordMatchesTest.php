<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Hash;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Hash\PasswordMatches;
use HarmonyIO\Validation\Rule\Rule;

class PasswordMatchesTest extends TestCase
{
    private const TEST_HASH = '$2y$10$PcRLWTmmlKptOuNnAZfmneSKIL7sSZ.j2ELZuNSncVSzqoovWNVzC';
    
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new PasswordMatches(self::TEST_HASH));
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new PasswordMatches(self::TEST_HASH))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new PasswordMatches(self::TEST_HASH))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new PasswordMatches(self::TEST_HASH))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new PasswordMatches(self::TEST_HASH))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new PasswordMatches(self::TEST_HASH))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new PasswordMatches(self::TEST_HASH))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new PasswordMatches(self::TEST_HASH))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new PasswordMatches(self::TEST_HASH))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenPasswordIsInvalid(): void
    {
        $this->markTestSkipped('Waiting for fix of: https://github.com/amphp/parallel-functions/issues/12');

        $this->assertFalse((new PasswordMatches(self::TEST_HASH))->validate('123456789'));
    }

    public function testValidateReturnsFalseWhenPasswordIsValid(): void
    {
        $this->markTestSkipped('Waiting for fix of: https://github.com/amphp/parallel-functions/issues/12');

        $this->assertTrue((new PasswordMatches(self::TEST_HASH))->validate('1234567890'));
    }
}
