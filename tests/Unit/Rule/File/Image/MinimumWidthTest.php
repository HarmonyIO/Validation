<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File\Image;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\File\Image\MinimumWidth;
use HarmonyIO\ValidationTest\Unit\Rule\FileTestCase;
use function Amp\Promise\wait;

class MinimumWidthTest extends FileTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, MinimumWidth::class, 200);
    }

    public function testValidateFailsWhenPassingAnUnsupportedImage(): void
    {
        /** @var Result $result */
        $result = wait((new MinimumWidth(200))->validate(TEST_DATA_DIR . '/file-mimetype-test.txt'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.Image.Image', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnImageWhichIsSmallerThanTheMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new MinimumWidth(201))->validate(TEST_DATA_DIR . '/image/200x400.png'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.Image.MinimumWidth', $result->getFirstError()->getMessage());
        $this->assertSame('width', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(201, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateSucceedsWhenPassingAnImageWhichExactlyMatchesTheMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new MinimumWidth(200))->validate(TEST_DATA_DIR . '/image/200x400.png'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnImageWhichIsLargerThanTheMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new MinimumWidth(199))->validate(TEST_DATA_DIR . '/image/200x400.png'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
