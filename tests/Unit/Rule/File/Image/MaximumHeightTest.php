<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File\Image;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\File\Image\MaximumHeight;
use HarmonyIO\ValidationTest\Unit\Rule\FileTestCase;
use function Amp\Promise\wait;

class MaximumHeightTest extends FileTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, MaximumHeight::class, 400);
    }

    public function testValidateFailsWhenPassingAnUnsupportedImage(): void
    {
        /** @var Result $result */
        $result = wait((new MaximumHeight(400))->validate(TEST_DATA_DIR . '/file-mimetype-test.txt'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.Image.Image', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnImageWhichIsLargerThanTheMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new MaximumHeight(399))->validate(TEST_DATA_DIR . '/image/200x400.png'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.Image.MaximumHeight', $result->getFirstError()->getMessage());
        $this->assertSame('height', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(399, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateSucceedsWhenPassingAnImageWhichExactlyMatchesTheMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new MaximumHeight(400))->validate(TEST_DATA_DIR . '/image/200x400.png'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnImageWhichIsSmallerThanTheMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new MaximumHeight(401))->validate(TEST_DATA_DIR . '/image/200x400.png'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
