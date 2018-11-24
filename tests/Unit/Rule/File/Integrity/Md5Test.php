<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File\Integrity;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\File\Integrity\Md5;
use HarmonyIO\Validation\Rule\Rule;

class Md5Test extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Md5('97b265118a38fb02e7087d30f63515c7'));
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Md5('97b265118a38fb02e7087d30f63515c7'))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Md5('97b265118a38fb02e7087d30f63515c7'))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Md5('97b265118a38fb02e7087d30f63515c7'))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Md5('97b265118a38fb02e7087d30f63515c7'))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Md5('97b265118a38fb02e7087d30f63515c7'))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Md5('97b265118a38fb02e7087d30f63515c7'))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Md5('97b265118a38fb02e7087d30f63515c7'))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Md5('97b265118a38fb02e7087d30f63515c7'))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenFileDoesNotExists(): void
    {
        $this->assertFalse((new Md5('97b265118a38fb02e7087d30f63515c7'))->validate(TEST_DATA_DIR . '/unknown-file.txt'));
    }

    public function testValidateReturnsFalseWhenFileDoesNotMatch(): void
    {
        $this->assertFalse((new Md5('97b265118a38fb02e7087d30f63515c7'))->validate(TEST_DATA_DIR . '/file-integrity-no-match-test.txt'));
    }

    public function testValidateReturnsTrueWhenImageIsValid(): void
    {
        $this->assertTrue((new Md5('97b265118a38fb02e7087d30f63515c7'))->validate(TEST_DATA_DIR . '/file-integrity-test.txt'));
    }
}
