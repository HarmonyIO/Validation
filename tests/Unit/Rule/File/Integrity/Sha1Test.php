<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File\Integrity;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\File\Integrity\Sha1;
use HarmonyIO\ValidationTest\Unit\Rule\FileTestCase;
use function Amp\Promise\wait;

class Sha1Test extends FileTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Sha1::class, 'f0f82bc3889d01ff54acbb3bfbd4d6e3cbb21964');
    }

    public function testValidateFailsWhenFileDoesNotMatch(): void
    {
        /** @var Result $result */
        // phpcs:ignore PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket
        $result = wait((new Sha1('f0f82bc3889d01ff54acbb3bfbd4d6e3cbb21964'))
            ->validate(TEST_DATA_DIR . '/file-integrity-no-match-test.txt')
        );

        $this->assertFalse($result->isValid());
        $this->assertSame('File.Integrity.Sha1', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenImageIsValid(): void
    {
        /** @var Result $result */
        // phpcs:ignore PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket
        $result = wait((new Sha1('f0f82bc3889d01ff54acbb3bfbd4d6e3cbb21964'))
            ->validate(TEST_DATA_DIR . '/file-integrity-test.txt')
        );

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
