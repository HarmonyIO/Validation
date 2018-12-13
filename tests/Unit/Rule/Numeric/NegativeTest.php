<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Numeric;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Numeric\Negative;
use HarmonyIO\ValidationTest\Unit\Rule\NumericTestCase;
use function Amp\Promise\wait;

class NegativeTest extends NumericTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Negative::class);
    }

    public function testValidateFailsWhenPassingInZeroAsAnInteger(): void
    {
        /** @var Result $result */
        $result = wait((new Negative())->validate(0));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Negative', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingInZeroAsAFloat(): void
    {
        /** @var Result $result */
        $result = wait((new Negative())->validate(0.0));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Negative', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingInZeroAsAnIntegerAsAString(): void
    {
        /** @var Result $result */
        $result = wait((new Negative())->validate('0'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Negative', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingInZeroAsAFloatAsAString(): void
    {
        /** @var Result $result */
        $result = wait((new Negative())->validate('0.0'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Negative', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingInAPositiveInteger(): void
    {
        /** @var Result $result */
        $result = wait((new Negative())->validate(1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Negative', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingInAPositiveAFloat(): void
    {
        /** @var Result $result */
        $result = wait((new Negative())->validate(0.1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Negative', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingInAPositiveIntegerAsAString(): void
    {
        /** @var Result $result */
        $result = wait((new Negative())->validate('1'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Negative', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingInAPositiveFloatAsAString(): void
    {
        /** @var Result $result */
        $result = wait((new Negative())->validate('0.1'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Negative', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingANegativeInteger(): void
    {
        /** @var Result $result */
        $result = wait((new Negative())->validate(-1));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingANegativeFloat(): void
    {
        /** @var Result $result */
        $result = wait((new Negative())->validate('-0.1'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingANegativeIntegerAsString(): void
    {
        /** @var Result $result */
        $result = wait((new Negative())->validate('-1'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingANegativeFloatAsString(): void
    {
        /** @var Result $result */
        $result = wait((new Negative())->validate('-0.1'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
