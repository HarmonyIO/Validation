<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Set;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Set\Countable;
use HarmonyIO\ValidationTest\Unit\Rule\CountableTestCase;
use function Amp\Promise\wait;

class CountableTest extends CountableTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Countable::class);
    }

    public function testValidateSucceedsWhenPassingAnEmptyArray(): void
    {
        /** @var Result $result */
        $result = wait((new Countable())->validate([]));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAFilledArray(): void
    {
        /** @var Result $result */
        $result = wait((new Countable())->validate(['foo', 'bar']));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWHenPassingACountableObject(): void
    {
        /** @var Result $result */
        $result = wait((new Countable())->validate(new \ArrayIterator(['foo', 'bar'])));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
