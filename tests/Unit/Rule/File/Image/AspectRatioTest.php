<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File\Image;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Exception\InvalidAspectRatio;
use HarmonyIO\Validation\Rule\File\Image\AspectRatio;
use HarmonyIO\Validation\Rule\Rule;

class AspectRatioTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new AspectRatio('4:3'));
    }

    public function testConstructorThrowsWhenPassingInAnInvalidAspectRatioString(): void
    {
        $this->expectException(InvalidAspectRatio::class);
        $this->expectExceptionMessage('The aspect ratio (`a:a`) could not be parsed.');

        new AspectRatio('a:a');
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new AspectRatio('4:3'))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new AspectRatio('4:3'))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new AspectRatio('4:3'))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new AspectRatio('4:3'))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new AspectRatio('4:3'))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new AspectRatio('4:3'))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new AspectRatio('4:3'))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new AspectRatio('4:3'))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenPassingAnNonExistentImage(): void
    {
        $this->assertFalse((new AspectRatio('4:3'))->validate(TEST_DATA_DIR . '/unknown-file.txt'));
    }

    public function testValidateReturnsTrueWhenPassingAnUnsupportedImage(): void
    {
        $this->assertFalse((new AspectRatio('4:3'))->validate(TEST_DATA_DIR . '/image/file-mime-type-test.txt'));
    }

    public function testValidateReturnsFalseWhenPassingAnImageWithIncorrectAspectRatio(): void
    {
        $this->assertFalse((new AspectRatio('4:3'))->validate(TEST_DATA_DIR . '/image/aspect-ratio-16-9.png'));
    }

    public function testValidateReturnsTrueWhenPassingAnImageWithCorrectAspectRatio(): void
    {
        $this->assertTrue((new AspectRatio('4:3'))->validate(TEST_DATA_DIR . '/image/aspect-ratio-4-3.png'));
    }
}
