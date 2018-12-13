<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\File\MinimumSize;
use HarmonyIO\ValidationTest\Unit\Rule\FileTestCase;
use function Amp\Promise\wait;

class MinimumSizeTest extends FileTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, MinimumSize::class, 6);
    }

    public function testValidateFailsWhenFileIsSmallerThanMinimumSize(): void
    {
        /** @var Result $result */
        $result = wait((new MinimumSize(7))->validate(TEST_DATA_DIR . '/file-size-test-6b.txt'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.MinimumSize', $result->getFirstError()->getMessage());
        $this->assertSame('size', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(7, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateSucceedsWhenFileIsExactlyMinimumSize(): void
    {
        /** @var Result $result */
        $result = wait((new MinimumSize(6))->validate(TEST_DATA_DIR . '/file-size-test-6b.txt'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenFileIsLargerThanMinimumSize(): void
    {
        /** @var Result $result */
        $result = wait((new MinimumSize(5))->validate(TEST_DATA_DIR . '/file-size-test-6b.txt'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
