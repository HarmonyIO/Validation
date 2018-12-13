<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File\Image\Type;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\File\Image\Type\Svg;
use HarmonyIO\ValidationTest\Unit\Rule\FileTestCase;
use function Amp\Promise\wait;

class SvgTest extends FileTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Svg::class);
    }

    public function testValidateFailsWhenNotMatchingMimeType(): void
    {
        /** @var Result $result */
        $result = wait((new Svg())->validate(TEST_DATA_DIR . '/image/mspaint.gif'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.MimeType', $result->getFirstError()->getMessage());
        $this->assertSame('mimeType', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame('image/svg', $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenImageContainsBrokenXml(): void
    {
        /** @var Result $result */
        $result = wait((new Svg())->validate(TEST_DATA_DIR . '/image/broken.svg'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.Image.Type.Svg.ValidElements', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenImageContainsInvalidElements(): void
    {
        /** @var Result $result */
        $result = wait((new Svg())->validate(TEST_DATA_DIR . '/image/invalid-elements.svg'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.Image.Type.Svg.ValidElements', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenImageContainsInvalidAttributes(): void
    {
        /** @var Result $result */
        $result = wait((new Svg())->validate(TEST_DATA_DIR . '/image/invalid-attributes.svg'));

        $this->assertFalse($result->isValid());
        $this->assertSame('File.Image.Type.Svg.ValidAttributes', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenImageIsValid(): void
    {
        /** @var Result $result */
        $result = wait((new Svg())->validate(TEST_DATA_DIR . '/image/example.svg'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
