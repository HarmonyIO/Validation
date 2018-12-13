<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File\Image\Type;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\File\Image\Type\Jpeg;
use HarmonyIO\ValidationTest\Unit\Rule\FileTestCase;
use function Amp\Promise\wait;

class JpegTest extends FileTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Jpeg::class);
    }

    public function testValidateFailsWhenNotMatchingMimeType(): void
    {
        /** @var Result $result */
        $result = wait((new Jpeg())->validate(TEST_DATA_DIR . '/image/mspaint.gif'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.MimeType', $result->getFirstError()->getMessage());
        $this->assertSame('mimeType', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame('image/jpeg', $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenImageIsCorrupted(): void
    {
        $this->markTestSkipped('GD errors are somehow being output regardless of the STFU operators, See https://bugs.php.net/bug.php?id=77195');

        /** @var Result $result */
        $result = wait((new Jpeg())->validate(TEST_DATA_DIR . '/image/broken-mspaint.jpeg'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.Image.Type.Jpeg', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenImageIsValid(): void
    {
        /** @var Result $result */
        $result = wait((new Jpeg())->validate(TEST_DATA_DIR . '/image/mspaint.jpeg'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
