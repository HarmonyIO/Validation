<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Set;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Set\MinimumLength;
use HarmonyIO\ValidationTest\Unit\Rule\CountableTestCase;
use function Amp\Promise\wait;

class MinimumLengthTest extends CountableTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, MinimumLength::class, 3);
    }

    public function testValidateFailsWhenPassingAnArrayWithLessItemsThanTheMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new MinimumLength(3))->validate([]));

        $this->assertFalse($result->isValid());
        $this->assertSame('Set.MinimumLength', $result->getFirstError()->getMessage());
        $this->assertSame('length', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(3, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAnArrayIteratorWithLessItemsThanTheMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new MinimumLength(3))->validate(new \ArrayIterator(['foo', 'bar'])));

        $this->assertFalse($result->isValid());
        $this->assertSame('Set.MinimumLength', $result->getFirstError()->getMessage());
        $this->assertSame('length', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(3, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateSucceedsWhenPassingAnArrayWithExactNumberOfItemsAsTheMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new MinimumLength(3))->validate(['foo', 'bar', 'baz']));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnArrayIteratorWithExactNumberOfItemsAsTheMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new MinimumLength(3))->validate(new \ArrayIterator(['foo', 'bar', 'baz'])));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnArrayWithMoreItemsThanTheMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new MinimumLength(3))->validate(['foo', 'bar', 'baz', 'qux']));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnArrayIteratorWithMoreItemsThanTheMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new MinimumLength(3))->validate(new \ArrayIterator(['foo', 'bar', 'baz', 'qux'])));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
