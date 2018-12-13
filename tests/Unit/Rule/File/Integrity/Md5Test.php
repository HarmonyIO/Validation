<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File\Integrity;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\File\Integrity\Md5;
use HarmonyIO\ValidationTest\Unit\Rule\FileTestCase;
use function Amp\Promise\wait;

class Md5Test extends FileTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Md5::class, '97b265118a38fb02e7087d30f63515c7');
    }

    public function testValidateFailsWhenFileDoesNotMatch(): void
    {
        /** @var Result $result */
        // phpcs:ignore PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket
        $result = wait((new Md5('97b265118a38fb02e7087d30f63515c7'))
            ->validate(TEST_DATA_DIR . '/file-integrity-no-match-test.txt')
        );

        $this->assertFalse($result->isValid());
        $this->assertSame('File.Integrity.Md5', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenImageIsValid(): void
    {
        /** @var Result $result */
        // phpcs:ignore PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket
        $result = wait((new Md5('97b265118a38fb02e7087d30f63515c7'))
            ->validate(TEST_DATA_DIR . '/file-integrity-test.txt')
        );

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
