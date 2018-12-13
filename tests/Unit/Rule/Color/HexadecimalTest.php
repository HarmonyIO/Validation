<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Color;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Color\Hexadecimal;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class HexadecimalTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Hexadecimal::class);
    }

    public function testValidateFailsWhenStringDoesNotStartWithPoundSign(): void
    {
        /** @var Result $result */
        $result = wait((new Hexadecimal())->validate('ff3300'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Color.Hexadecimal', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringContainsACharacterOutsideOfTheHexRange(): void
    {
        /** @var Result $result */
        $result = wait((new Hexadecimal())->validate('#gf3300'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Color.Hexadecimal', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenValueIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Hexadecimal())->validate('#ff330'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Color.Hexadecimal', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenValueIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Hexadecimal())->validate('#ff33000'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Color.Hexadecimal', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsOnValidLowerCaseValue(): void
    {
        /** @var Result $result */
        $result = wait((new Hexadecimal())->validate('#ff3300'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsOnValidUpperCaseValue(): void
    {
        /** @var Result $result */
        $result = wait((new Hexadecimal())->validate('#FF3300'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
