<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File\Image;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\File\Image\Image;
use HarmonyIO\Validation\Rule\Rule;

class ImageTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Image());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Image())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Image())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Image())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Image())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Image())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Image())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Image())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Image())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenFileDoesNotExists(): void
    {
        $this->assertFalse((new Image())->validate(TEST_DATA_DIR . '/unknown-file.txt'));
    }

    public function testValidateReturnsTrueWhenPassingABmpFile(): void
    {
        $this->assertTrue((new Image())->validate(TEST_DATA_DIR . '/image/mspaint.bmp'));
    }

    public function testValidateReturnsTrueWhenPassingAGifFile(): void
    {
        $this->assertTrue((new Image())->validate(TEST_DATA_DIR . '/image/mspaint.gif'));
    }

    public function testValidateReturnsTrueWhenPassingAJpegFile(): void
    {
        $this->assertTrue((new Image())->validate(TEST_DATA_DIR . '/image/mspaint.jpeg'));
    }

    public function testValidateReturnsTrueWhenPassingAPngFile(): void
    {
        $this->assertTrue((new Image())->validate(TEST_DATA_DIR . '/image/mspaint.png'));
    }

    public function testValidateReturnsFalseWhenPassingAnUnsupportedFile(): void
    {
        $this->assertFalse((new Image())->validate(TEST_DATA_DIR . '/image/file-mime-type-test.txt'));
    }
}
