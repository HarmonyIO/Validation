<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule;

use HarmonyIO\Validation\Result\Result;
use function Amp\Promise\wait;

class FileTestCase extends StringTestCase
{
    public function testValidateFailsWhenFileDoesNotExists(): void
    {
        /** @var Result $result */
        $result = wait($this->testObject->validate(TEST_DATA_DIR . '/unknown-file.txt'));

        $this->assertFalse($result->isValid());
        $this->assertSame('FileSystem.File', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingADirectory(): void
    {
        /** @var Result $result */
        $result = wait($this->testObject->validate(TEST_DATA_DIR . '/file-system/existing'));

        $this->assertFalse($result->isValid());
        $this->assertSame('FileSystem.File', $result->getFirstError()->getMessage());
    }
}
