<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Text;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Text\NoControlCharacters;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class NoControlCharactersTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, NoControlCharacters::class, 10);
    }

    public function testValidateFailsWhenPassingAStringContainingControlCharacters(): void
    {
        /** @var Result $result */
        $result = wait((new NoControlCharacters())->validate('€€€€€€€€€' . chr(0)));

        $this->assertFalse($result->isValid());
        $this->assertSame('Text.NoControlCharacters', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAStringWithoutControlCharacters(): void
    {
        /** @var Result $result */
        $result = wait((new NoControlCharacters())->validate('€€€€€€€€€€€'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
