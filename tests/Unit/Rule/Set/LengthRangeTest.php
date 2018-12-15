<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Set;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Set\LengthRange;
use HarmonyIO\ValidationTest\Unit\Rule\CountableTestCase;
use function Amp\Promise\wait;

class LengthRangeTest extends CountableTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, LengthRange::class, 3, 4);
    }

    public function testValidateFailsWhenPassingAnArrayWithLessItemsThanTheMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new LengthRange(3, 4))->validate([]));

        $this->assertFalse($result->isValid());
        $this->assertSame('Set.MinimumLength', $result->getFirstError()->getMessage());
        $this->assertSame('length', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(3, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAnArrayIteratorWithLessItemsThanTheMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new LengthRange(3, 4))->validate(new \ArrayIterator(['foo', 'bar'])));

        $this->assertFalse($result->isValid());
        $this->assertSame('Set.MinimumLength', $result->getFirstError()->getMessage());
        $this->assertSame('length', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(3, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAnArrayWithMoreItemsThanTheMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new LengthRange(3, 4))->validate(['foo', 'bar', 'baz', 'qux', 'quux']));

        $this->assertFalse($result->isValid());
        $this->assertSame('Set.MaximumLength', $result->getFirstError()->getMessage());
        $this->assertSame('length', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(4, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAnArrayIteratorWithMoreItemsThanTheMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new LengthRange(3, 4))->validate(new \ArrayIterator(['foo', 'bar', 'baz', 'qux', 'quux'])));

        $this->assertFalse($result->isValid());
        $this->assertSame('Set.MaximumLength', $result->getFirstError()->getMessage());
        $this->assertSame('length', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(4, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateSucceedsWhenPassingAnArrayWithExactNumberOfItemsAsTheMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new LengthRange(3, 4))->validate(['foo', 'bar', 'baz']));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnArrayIteratorWithExactNumberOfItemsAsTheMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new LengthRange(3, 4))->validate(new \ArrayIterator(['foo', 'bar', 'baz'])));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnArrayWithExactNumberOfItemsAsTheMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new LengthRange(3, 4))->validate(['foo', 'bar', 'baz', 'qux']));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnArrayIteratorWithExactNumberOfItemsAsTheMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new LengthRange(3, 4))->validate(new \ArrayIterator(['foo', 'bar', 'baz', 'qux'])));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnArrayWithNumberOfItemsInRange(): void
    {
        /** @var Result $result */
        $result = wait((new LengthRange(3, 5))->validate(['foo', 'bar', 'baz', 'qux']));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnArrayIteratorWithNumberOfItemsInRange(): void
    {
        /** @var Result $result */
        $result = wait((new LengthRange(3, 5))->validate(new \ArrayIterator(['foo', 'bar', 'baz', 'qux'])));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
