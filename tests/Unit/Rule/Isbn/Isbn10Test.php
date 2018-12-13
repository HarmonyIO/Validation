<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Isbn;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Isbn\Isbn10;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class Isbn10Test extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Isbn10::class);
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Isbn10())->validate('897013750'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Isbn.Isbn10', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Isbn10())->validate('89701375066'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Isbn.Isbn10', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringContainsInvalidCharacters(): void
    {
        /** @var Result $result */
        $result = wait((new Isbn10())->validate('897013750y'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Isbn.Isbn10', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumDoesNotMatch(): void
    {
        /** @var Result $result */
        $result = wait((new Isbn10())->validate('0345391803'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Isbn.Isbn10', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsTrueWhenValid(): void
    {
        /** @var Result $result */
        $result = wait((new Isbn10())->validate('0345391802'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenValidWithLowercaseCheckDigitX(): void
    {
        /** @var Result $result */
        $result = wait((new Isbn10())->validate('043942089x'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenValidWithUppercaseCheckDigitX(): void
    {
        /** @var Result $result */
        $result = wait((new Isbn10())->validate('043942089X'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
