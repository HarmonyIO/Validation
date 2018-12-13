<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Text;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Text\MaximumLength;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class MaximumLengthTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, MaximumLength::class, 10);
    }

    public function testValidateFailsWhenPassingAStringLongerThanTheMaximumLength(): void
    {
        /** @var Result $result */
        $result = wait((new MaximumLength(10))->validate('€€€€€€€€€€€'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Text.MaximumLength', $result->getFirstError()->getMessage());
        $this->assertSame('length', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(10, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateSucceedsWhenPassingAStringSmallerThanTheMaximumLength(): void
    {
        /** @var Result $result */
        $result = wait((new MaximumLength(10))->validate('€€€€€€€€€'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAStringWithExactlyTheMaximumLength(): void
    {
        /** @var Result $result */
        $result = wait((new MaximumLength(10))->validate('€€€€€€€€€€'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
