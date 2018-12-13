<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Numeric;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Numeric\Positive;
use HarmonyIO\ValidationTest\Unit\Rule\NumericTestCase;
use function Amp\Promise\wait;

class PositiveTest extends NumericTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Positive::class);
    }

    public function testValidateFailsWhenPassingInANegativeInteger(): void
    {
        /** @var Result $result */
        $result = wait((new Positive())->validate(-1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Positive', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingInANegativeIntegerAsAString(): void
    {
        /** @var Result $result */
        $result = wait((new Positive())->validate('-1'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Positive', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingInANegativeFloat(): void
    {
        /** @var Result $result */
        $result = wait((new Positive())->validate(-0.1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Positive', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingInANegativeFloatAsAString(): void
    {
        /** @var Result $result */
        $result = wait((new Positive())->validate('-0.1'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Positive', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingInZeroAsAnInteger(): void
    {
        /** @var Result $result */
        $result = wait((new Positive())->validate(0));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingInZeroAsAFloat(): void
    {
        /** @var Result $result */
        $result = wait((new Positive())->validate(0.0));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingInZeroAsAString(): void
    {
        /** @var Result $result */
        $result = wait((new Positive())->validate('0.0'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingInAPositiveInteger(): void
    {
        /** @var Result $result */
        $result = wait((new Positive())->validate(1));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingInAPositiveIntegerAsAString(): void
    {
        /** @var Result $result */
        $result = wait((new Positive())->validate('1'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingInAPositiveFloat(): void
    {
        /** @var Result $result */
        $result = wait((new Positive())->validate(0.1));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingInAPositiveFloatAsAString(): void
    {
        /** @var Result $result */
        $result = wait((new Positive())->validate('0.1'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
