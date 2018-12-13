<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Numeric;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Numeric\Minimum;
use HarmonyIO\ValidationTest\Unit\Rule\NumericTestCase;
use function Amp\Promise\wait;

class MinimumTest extends NumericTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Minimum::class, 10);
    }

    public function testValidateFailsWhenPassingAnIntegerWhichIsSmallerThanMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new Minimum(10))->validate(9));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Minimum', $result->getFirstError()->getMessage());
        $this->assertSame('minimum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(10, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAnIntegerAsAStringWhichIsSmallerThanMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new Minimum(10))->validate('9'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Minimum', $result->getFirstError()->getMessage());
        $this->assertSame('minimum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(10, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAFloatWhichIsSmallerThanMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new Minimum(10))->validate(9.9));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Minimum', $result->getFirstError()->getMessage());
        $this->assertSame('minimum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(10, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAFloatAsAStringWhichIsSmallerThanMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new Minimum(10))->validate('9.9'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Minimum', $result->getFirstError()->getMessage());
        $this->assertSame('minimum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(10, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateSucceedsWhenPassingAnIntegerWhichIsExactlyMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new Minimum(10))->validate(10));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnIntegerWhichIsLargerThanMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new Minimum(10))->validate(11));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnIntegerAsAStringWhichIsExactlyMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new Minimum(10))->validate('10'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnIntegerAsAStringWhichIsLargerThanMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new Minimum(10))->validate('11'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAFloatWhichIsExactlyMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new Minimum(10))->validate(10.0));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAFloatWhichIsLargerThanMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new Minimum(10))->validate(10.1));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAFloatAsAStringWhichIsExactlyMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new Minimum(10))->validate('10.0'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAFloatAsAStringWhichIsLargerThanMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new Minimum(10))->validate('10.1'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
