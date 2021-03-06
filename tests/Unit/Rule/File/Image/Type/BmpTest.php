<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File\Image\Type;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\File\Image\Type\Bmp;
use HarmonyIO\ValidationTest\Unit\Rule\FileTestCase;
use function Amp\Promise\wait;

class BmpTest extends FileTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Bmp::class);
    }

    public function testValidateFailsWhenNotMatchingMimeType(): void
    {
        /** @var Result $result */
        $result = wait((new Bmp())->validate(TEST_DATA_DIR . '/image/mspaint.gif'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.Image.Type.Bmp', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenImageIsCorrupted(): void
    {
        /** @var Result $result */
        $result = wait((new Bmp())->validate(TEST_DATA_DIR . '/image/broken-mspaint.bmp'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.Image.Type.Bmp', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenImageIsValid(): void
    {
        /** @var Result $result */
        $result = wait((new Bmp())->validate(TEST_DATA_DIR . '/image/mspaint.bmp'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
