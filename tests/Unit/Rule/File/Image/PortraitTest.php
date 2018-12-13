<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File\Image;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\File\Image\Portrait;
use HarmonyIO\ValidationTest\Unit\Rule\FileTestCase;
use function Amp\Promise\wait;

class PortraitTest extends FileTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Portrait::class);
    }

    public function testValidateFailsWhenPassingAnUnsupportedImage(): void
    {
        /** @var Result $result */
        $result = wait((new Portrait())->validate(TEST_DATA_DIR . '/file-mimetype-test.txt'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.Image.Image', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnImageWhichWidthIsTheSameAsItsHeight(): void
    {
        /** @var Result $result */
        $result = wait((new Portrait())->validate(TEST_DATA_DIR . '/image/400x400.png'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.Image.Portrait', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnImageWhichWidthIsBiggerThanItsHeight(): void
    {
        /** @var Result $result */
        $result = wait((new Portrait())->validate(TEST_DATA_DIR . '/image/400x200.png'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.Image.Portrait', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAnImageWhichHeightIsBiggerThanItsWidth(): void
    {
        /** @var Result $result */
        $result = wait((new Portrait())->validate(TEST_DATA_DIR . '/image/200x400.png'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
