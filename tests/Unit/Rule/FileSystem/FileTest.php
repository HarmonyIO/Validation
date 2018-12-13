<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\FileSystem;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\FileSystem\File;
use HarmonyIO\ValidationTest\Unit\Rule\FileTestCase;
use function Amp\Promise\wait;

class FileTest extends FileTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, File::class);
    }

    public function testValidateSucceedsWhenAFileThatDoesExist(): void
    {
        /** @var Result $result */
        $result = wait($this->testObject->validate(TEST_DATA_DIR . '/file-system/existing/existing.txt'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
