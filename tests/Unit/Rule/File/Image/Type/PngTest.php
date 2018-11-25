<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File\Image\Type;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\File\Image\Type\Png;
use HarmonyIO\Validation\Rule\Rule;

class PngTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Png());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Png())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Png())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Png())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Png())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Png())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Png())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Png())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Png())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenFileDoesNotExists(): void
    {
        $this->assertFalse((new Png())->validate(TEST_DATA_DIR . '/unknown-file.txt'));
    }

    public function testValidateReturnsFalseWhenPassingInADirectory(): void
    {
        $this->assertFalse((new Png())->validate(TEST_DATA_DIR . '/file-system/existing'));
    }

    public function testValidateReturnsFalseWhenNotMatchingMimeType(): void
    {
        $this->assertFalse((new Png())->validate(TEST_DATA_DIR . '/image/mspaint.gif'));
    }

    public function testValidateReturnsFalseWhenImageIsCorrupted(): void
    {
        $this->assertFalse((new Png())->validate(TEST_DATA_DIR . '/image/broken-mspaint.png'));
    }

    public function testValidateReturnsTrueWhenImageIsValid(): void
    {
        $this->assertTrue((new Png())->validate(TEST_DATA_DIR . '/image/mspaint.png'));
    }
}
