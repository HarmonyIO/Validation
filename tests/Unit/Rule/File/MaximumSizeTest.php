<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\File\MaximumSize;
use HarmonyIO\Validation\Rule\Rule;

class MaximumSizeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new MaximumSize(3));
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new MaximumSize(3))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new MaximumSize(3))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new MaximumSize(3))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new MaximumSize(3))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new MaximumSize(3))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new MaximumSize(3))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new MaximumSize(3))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new MaximumSize(3))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenFileDoesNotExists(): void
    {
        $this->assertFalse((new MaximumSize(3))->validate(TEST_DATA_DIR . '/unknown-file.txt'));
    }

    public function testValidateReturnsFalseWhenPassingInADirectory(): void
    {
        $this->assertFalse((new MaximumSize(3))->validate(TEST_DATA_DIR . '/file-system/existing'));
    }

    public function testValidateReturnsTrueWhenFileIsSmallerThanMaximumSize(): void
    {
        $this->assertTrue((new MaximumSize(7))->validate(TEST_DATA_DIR . '/file-size-test-6b.txt'));
    }

    public function testValidateReturnsTrueWhenFileIsExactlyMaximumSize(): void
    {
        $this->assertTrue((new MaximumSize(6))->validate(TEST_DATA_DIR . '/file-size-test-6b.txt'));
    }

    public function testValidateReturnsFalseWhenFileIsLargerThanMaximumSize(): void
    {
        $this->assertFalse((new MaximumSize(5))->validate(TEST_DATA_DIR . '/file-size-test-6b.txt'));
    }
}
