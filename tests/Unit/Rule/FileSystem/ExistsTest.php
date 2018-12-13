<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\FileSystem;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\FileSystem\Exists;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class ExistsTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Exists::class);
    }

    public function testValidateFailsWhenPassingAnUnExistingPath(): void
    {
        /** @var Result $result */
        $result = wait((new Exists())->validate(TEST_DATA_DIR . '/unknown-file.txt'));

        $this->assertFalse($result->isValid());
        $this->assertSame('FileSystem.Exists', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAnExistingDirectory(): void
    {
        /** @var Result $result */
        $result = wait((new Exists())->validate(TEST_DATA_DIR . '/file-system/existing'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnExistingFile(): void
    {
        /** @var Result $result */
        $result = wait((new Exists())->validate(TEST_DATA_DIR . '/file-system/existing/existing.txt'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
