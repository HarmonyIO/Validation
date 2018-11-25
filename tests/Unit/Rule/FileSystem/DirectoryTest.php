<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\FileSystem;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\FileSystem\Directory;
use HarmonyIO\Validation\Rule\Rule;

class DirectoryTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Directory());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Directory())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Directory())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Directory())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Directory())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Directory())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Directory())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Directory())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Directory())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenPassingAnUnExistingPath(): void
    {
        $this->assertFalse((new Directory())->validate(TEST_DATA_DIR . '/unknown-file.txt'));
    }

    public function testValidateReturnsFalseWhenPassingAnExistingFile(): void
    {
        $this->assertFalse((new Directory())->validate(TEST_DATA_DIR . '/file-system/existing/existing.txt'));
    }

    public function testValidateReturnsTrueWhenPassingAnExistingDirectory(): void
    {
        $this->assertTrue((new Directory())->validate(TEST_DATA_DIR . '/file-system/existing'));
    }
}
