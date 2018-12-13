<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Text;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Text\MinimumLength;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class MinimumLengthTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, MinimumLength::class, 10);
    }

    public function testValidateFailsWhenPassingAStringSmallerThanTheMinimumLength(): void
    {
        /** @var Result $result */
        $result = wait((new MinimumLength(10))->validate('€€€€€€€€€'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Text.MinimumLength', $result->getFirstError()->getMessage());
        $this->assertSame('length', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(10, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateSucceedsWhenPassingAStringLongerThanTheMinimumLength(): void
    {
        /** @var Result $result */
        $result = wait((new MinimumLength(10))->validate('€€€€€€€€€€€'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAStringWithExactlyTheMinimumLength(): void
    {
        /** @var Result $result */
        $result = wait((new MinimumLength(10))->validate('€€€€€€€€€€'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
