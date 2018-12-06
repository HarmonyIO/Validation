<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Isbn;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Isbn\Isbn13;
use HarmonyIO\Validation\Rule\Rule;

class Isbn13Test extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Isbn13());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Isbn13())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Isbn13())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Isbn13())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Isbn13())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Isbn13())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Isbn13())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Isbn13())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Isbn13())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenStringIsTooShort(): void
    {
        $this->assertFalse((new Isbn13())->validate('978897013750'));
    }

    public function testValidateReturnsFalseWhenStringIsTooLong(): void
    {
        $this->assertFalse((new Isbn13())->validate('97889701375066'));
    }

    public function testValidateReturnsFalseWhenStringContainsInvalidCharacters(): void
    {
        $this->assertFalse((new Isbn13())->validate('978897013750x'));
    }

    public function testValidateReturnsFalseWhenChecksumDoesNotMatch(): void
    {
        $this->assertFalse((new Isbn13())->validate('9788970137507'));
    }

    public function testValidateReturnsTrueWhenValid(): void
    {
        $this->assertTrue((new Isbn13())->validate('9788970137506'));
    }
}
