<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Isbn;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Isbn\Isbn13;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class Isbn13Test extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Isbn13::class);
    }

    public function testValidateFailsWhenStringIsTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Isbn13())->validate('978897013750'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Isbn.Isbn13', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringIsTooLong(): void
    {
        /** @var Result $result */
        $result = wait((new Isbn13())->validate('97889701375066'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Isbn.Isbn13', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenStringContainsInvalidCharacters(): void
    {
        /** @var Result $result */
        $result = wait((new Isbn13())->validate('978897013750x'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Isbn.Isbn13', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenChecksumDoesNotMatch(): void
    {
        /** @var Result $result */
        $result = wait((new Isbn13())->validate('9788970137507'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Isbn.Isbn13', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenValid(): void
    {
        /** @var Result $result */
        $result = wait((new Isbn13())->validate('9788970137506'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
