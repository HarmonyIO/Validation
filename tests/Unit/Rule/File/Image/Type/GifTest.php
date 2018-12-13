<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File\Image\Type;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\File\Image\Type\Gif;
use HarmonyIO\ValidationTest\Unit\Rule\FileTestCase;
use function Amp\Promise\wait;

class GifTest extends FileTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Gif::class);
    }

    public function testValidateFailsWhenNotMatchingMimeType(): void
    {
        /** @var Result $result */
        $result = wait((new Gif())->validate(TEST_DATA_DIR . '/image/mspaint.jpeg'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.MimeType', $result->getFirstError()->getMessage());
        $this->assertSame('mimeType', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame('image/gif', $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenImageIsCorrupted(): void
    {
        /** @var Result $result */
        $result = wait((new Gif())->validate(TEST_DATA_DIR . '/image/broken-mspaint.gif'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.Image.Type.Gif', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenImageIsValid(): void
    {
        /** @var Result $result */
        $result = wait((new Gif())->validate(TEST_DATA_DIR . '/image/mspaint.gif'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
