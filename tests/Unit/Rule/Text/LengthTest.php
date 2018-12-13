<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Text;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Text\Length;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class LengthTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Length::class, 10);
    }

    public function testValidateFailsWhenPassingAStringSmallerThanTheLength(): void
    {
        /** @var Result $result */
        $result = wait((new Length(10))->validate('€€€€€€€€€'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Text.Length', $result->getFirstError()->getMessage());
        $this->assertSame('length', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(10, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAStringLongerThanTheLength(): void
    {
        /** @var Result $result */
        $result = wait((new Length(10))->validate('€€€€€€€€€€€'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Text.Length', $result->getFirstError()->getMessage());
        $this->assertSame('length', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(10, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateSucceedsWhenPassingAStringWithExactlyTheLength(): void
    {
        /** @var Result $result */
        $result = wait((new Length(10))->validate('€€€€€€€€€€'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
