<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File\Integrity;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\File\Integrity\Sha1;
use HarmonyIO\Validation\Rule\Rule;

class Sha1Test extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Sha1('f0f82bc3889d01ff54acbb3bfbd4d6e3cbb21964'));
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Sha1('f0f82bc3889d01ff54acbb3bfbd4d6e3cbb21964'))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Sha1('f0f82bc3889d01ff54acbb3bfbd4d6e3cbb21964'))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Sha1('f0f82bc3889d01ff54acbb3bfbd4d6e3cbb21964'))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Sha1('f0f82bc3889d01ff54acbb3bfbd4d6e3cbb21964'))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Sha1('f0f82bc3889d01ff54acbb3bfbd4d6e3cbb21964'))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Sha1('f0f82bc3889d01ff54acbb3bfbd4d6e3cbb21964'))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Sha1('f0f82bc3889d01ff54acbb3bfbd4d6e3cbb21964'))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Sha1('f0f82bc3889d01ff54acbb3bfbd4d6e3cbb21964'))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenFileDoesNotExists(): void
    {
        $this->assertFalse((new Sha1('f0f82bc3889d01ff54acbb3bfbd4d6e3cbb21964'))->validate(TEST_DATA_DIR . '/unknown-file.txt'));
    }

    public function testValidateReturnsFalseWhenPassingInADirectory(): void
    {
        $this->assertFalse((new Sha1('f0f82bc3889d01ff54acbb3bfbd4d6e3cbb21964'))->validate(TEST_DATA_DIR . '/file-system/existing'));
    }

    public function testValidateReturnsFalseWhenFileDoesNotMatch(): void
    {
        $this->assertFalse((new Sha1('f0f82bc3889d01ff54acbb3bfbd4d6e3cbb21964'))->validate(TEST_DATA_DIR . '/file-integrity-no-match-test.txt'));
    }

    public function testValidateReturnsTrueWhenImageIsValid(): void
    {
        $this->assertTrue((new Sha1('f0f82bc3889d01ff54acbb3bfbd4d6e3cbb21964'))->validate(TEST_DATA_DIR . '/file-integrity-test.txt'));
    }
}
