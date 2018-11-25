<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\File\MimeType;
use HarmonyIO\Validation\Rule\Rule;

class MimeTypeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new MimeType('plain/text'));
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new MimeType('plain/text'))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new MimeType('plain/text'))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new MimeType('plain/text'))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new MimeType('plain/text'))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new MimeType('plain/text'))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new MimeType('plain/text'))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new MimeType('plain/text'))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new MimeType('plain/text'))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenFileDoesNotExists(): void
    {
        $this->assertFalse((new MimeType('plain/text'))->validate(TEST_DATA_DIR . '/unknown-file.txt'));
    }

    public function testValidateReturnsFalseWhenPassingInADirectory(): void
    {
        $this->assertFalse((new MimeType('plain/text'))->validate(TEST_DATA_DIR . '/file-system/existing'));
    }

    public function testValidateReturnsTrueWhenFileMatchesMimeType(): void
    {
        $this->assertTrue((new MimeType('text/plain'))->validate(TEST_DATA_DIR . '/file-mimetype-test.txt'));
    }

    public function testValidateReturnsFalseWhenNotMatchingMimeType(): void
    {
        $this->assertFalse((new MimeType('application/json'))->validate(TEST_DATA_DIR . '/file-mimetype-test.txt'));
    }
}
