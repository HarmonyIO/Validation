<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Set;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Set\MaximumLength;
use HarmonyIO\ValidationTest\Unit\Rule\CountableTestCase;
use function Amp\Promise\wait;

class MaximumLengthTest extends CountableTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, MaximumLength::class, 3);
    }

    public function testValidateFailsWhenPassingAnArrayWithMoreItemsThanTheMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new MaximumLength(3))->validate(['foo', 'bar', 'baz', 'qux']));

        $this->assertFalse($result->isValid());
        $this->assertSame('Set.MaximumLength', $result->getFirstError()->getMessage());
        $this->assertSame('length', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(3, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAnArrayIteratorWithMoreItemsThanTheMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new MaximumLength(3))->validate(new \ArrayIterator(['foo', 'bar', 'baz', 'qux'])));

        $this->assertFalse($result->isValid());
        $this->assertSame('Set.MaximumLength', $result->getFirstError()->getMessage());
        $this->assertSame('length', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(3, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateSucceedsWhenPassingAnArrayWithExactNumberOfItemsAsTheMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new MaximumLength(3))->validate(['foo', 'bar', 'baz']));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnArrayIteratorWithExactNumberOfItemsAsTheMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new MaximumLength(3))->validate(new \ArrayIterator(['foo', 'bar', 'baz'])));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnArrayWithLessItemsThanTheMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new MaximumLength(3))->validate(['foo', 'bar']));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnArrayIteratorWithLessItemsThanTheMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new MaximumLength(3))->validate(new \ArrayIterator(['foo', 'bar'])));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
