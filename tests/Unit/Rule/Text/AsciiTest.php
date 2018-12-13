<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Text;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Text\Ascii;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class AsciiTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Ascii::class);
    }

    public function testValidateFailsWhenPassingAnUtf8String(): void
    {
        /** @var Result $result */
        $result = wait((new Ascii())->validate('€'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Text.Ascii', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAnAsciiString(): void
    {
        /** @var Result $result */
        $result = wait((new Ascii())->validate(
            ' !"#$%&\\\'() * +,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\] ^ _`abcdefghijklmnopqrstuvwxyz{|}~'
        ));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
