<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Text;

use HarmonyIO\Validation\Exception\InvalidNumericalRange;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Text\LengthRange;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class LengthRangeTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, LengthRange::class, 10, 12);
    }

    public function testConstructorThrowsUpWhenMinimumLengthIsGreaterThanTheMaximumLength(): void
    {
        $this->expectException(InvalidNumericalRange::class);
        $this->expectExceptionMessage('The minimum (`12`) can not be greater than the maximum (`10`).');

        new LengthRange(12, 10);
    }

    public function testValidateFailsWhenPassingAStringSmallerThanTheMinimumLength(): void
    {
        /** @var Result $result */
        $result = wait((new LengthRange(10, 12))->validate('€€€€€€€€€'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Text.MinimumLength', $result->getFirstError()->getMessage());
        $this->assertSame('length', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(10, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAStringLargerThanTheMaximumLength(): void
    {
        /** @var Result $result */
        $result = wait((new LengthRange(10, 12))->validate('€€€€€€€€€€€€€'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Text.MaximumLength', $result->getFirstError()->getMessage());
        $this->assertSame('length', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(12, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateSucceedsWhenPassingAStringLargerThanTheMinimumLengthAndSmallerThanMaximumLength(): void
    {
        /** @var Result $result */
        $result = wait((new LengthRange(10, 12))->validate('€€€€€€€€€€€'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAStringWithExactlyTheMinimumLength(): void
    {
        /** @var Result $result */
        $result = wait((new LengthRange(10, 12))->validate('€€€€€€€€€€'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAStringExactlyTheMaximumLength(): void
    {
        /** @var Result $result */
        $result = wait((new LengthRange(10, 12))->validate('€€€€€€€€€€€€'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
