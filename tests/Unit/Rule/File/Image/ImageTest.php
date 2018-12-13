<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File\Image;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\File\Image\Image;
use HarmonyIO\ValidationTest\Unit\Rule\FileTestCase;
use function Amp\Promise\wait;

class ImageTest extends FileTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Image::class);
    }

    public function testValidateFailsWhenPassingAnUnsupportedFile(): void
    {
        /** @var Result $result */
        $result = wait((new Image())->validate(TEST_DATA_DIR . '/file-mimetype-test.txt'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.Image.Image', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingABmpFile(): void
    {
        /** @var Result $result */
        $result = wait((new Image())->validate(TEST_DATA_DIR . '/image/mspaint.bmp'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAGifFile(): void
    {
        /** @var Result $result */
        $result = wait((new Image())->validate(TEST_DATA_DIR . '/image/mspaint.gif'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAJpegFile(): void
    {
        /** @var Result $result */
        $result = wait((new Image())->validate(TEST_DATA_DIR . '/image/mspaint.jpeg'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAPngFile(): void
    {
        /** @var Result $result */
        $result = wait((new Image())->validate(TEST_DATA_DIR . '/image/mspaint.png'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnSvgFile(): void
    {
        /** @var Result $result */
        $result = wait((new Image())->validate(TEST_DATA_DIR . '/image/example.svg'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
