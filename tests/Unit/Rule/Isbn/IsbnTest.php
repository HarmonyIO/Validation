<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Isbn;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Isbn\Isbn;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class IsbnTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Isbn::class);
    }

    public function testValidateFailsWhenPassingInAnInvalidIsbn10(): void
    {
        /** @var Result $result */
        $result = wait((new Isbn())->validate('0345391803'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Isbn.Isbn', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingInAnInvalidIsbn13(): void
    {
        /** @var Result $result */
        $result = wait((new Isbn())->validate('9788970137507'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Isbn.Isbn', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingInAValidIsbn10(): void
    {
        /** @var Result $result */
        $result = wait((new Isbn())->validate('0345391802'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingInAValidIsbn13(): void
    {
        /** @var Result $result */
        $result = wait((new Isbn())->validate('9788970137506'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
