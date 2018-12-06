<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Isbn;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Isbn\Isbn;
use HarmonyIO\Validation\Rule\Rule;

class IsbnTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Isbn());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Isbn())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Isbn())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Isbn())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Isbn())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Isbn())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Isbn())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Isbn())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Isbn())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenPassingInAnInvalidIsbn10(): void
    {
            $this->assertFalse((new Isbn())->validate('0345391803'));
    }

    public function testValidateReturnsFalseWhenPassingInAnInvalidIsbn13(): void
    {
        $this->assertFalse((new Isbn())->validate('9788970137507'));
    }

    public function testValidateReturnsTrueWhenPassingInAValidIsbn10(): void
    {
        $this->assertTrue((new Isbn())->validate('0345391802'));
    }

    public function testValidateReturnsTrueWhenPassingInAValidIsbn13(): void
    {
        $this->assertTrue((new Isbn())->validate('9788970137506'));
    }
}
