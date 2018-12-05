<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Color;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Color\Hexadecimal;
use HarmonyIO\Validation\Rule\Rule;

class HexadecimalTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Hexadecimal());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Hexadecimal())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Hexadecimal())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Hexadecimal())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Hexadecimal())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Hexadecimal())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Hexadecimal())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Hexadecimal())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Hexadecimal())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenStringDoesNotStartWithPoundSign(): void
    {
        $this->assertFalse((new Hexadecimal())->validate('ff3300'));
    }

    public function testValidateReturnsFalseWhenStringContainsACharacterOutsideOfTheHexRange(): void
    {
        $this->assertFalse((new Hexadecimal())->validate('#gf3300'));
    }

    public function testValidateReturnsFalseWhenValueIsTooShort(): void
    {
        $this->assertFalse((new Hexadecimal())->validate('#ff330'));
    }

    public function testValidateReturnsFalseWhenValueIsTooLong(): void
    {
        $this->assertFalse((new Hexadecimal())->validate('#ff33000'));
    }

    public function testValidateReturnsTrueOnValidLowerCaseValue(): void
    {
        $this->assertFalse((new Hexadecimal())->validate('#ff3300'));
    }

    public function testValidateReturnsTrueOnValidUpperCaseValue(): void
    {
        $this->assertFalse((new Hexadecimal())->validate('#FF3300'));
    }
}
