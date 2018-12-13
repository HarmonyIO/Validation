<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Numeric;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Numeric\NumericType;
use HarmonyIO\ValidationTest\Unit\Rule\NumericTestCase;
use function Amp\Promise\wait;

class NumericTypeTest extends NumericTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, NumericType::class);
    }

    public function testValidateSucceedsWhenPassingAnInteger(): void
    {
        /** @var Result $result */
        $result = wait((new NumericType())->validate(1));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAFloat(): void
    {
        /** @var Result $result */
        $result = wait((new NumericType())->validate(1.1));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnIntegerAsAString(): void
    {
        /** @var Result $result */
        $result = wait((new NumericType())->validate('1'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAFloatAsAString(): void
    {
        /** @var Result $result */
        $result = wait((new NumericType())->validate('1.1'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
