<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Numeric;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Numeric\Maximum;
use HarmonyIO\ValidationTest\Unit\Rule\NumericTestCase;
use function Amp\Promise\wait;

class MaximumTest extends NumericTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Maximum::class, 10);
    }

    public function testValidateFailsWhenPassingAnIntegerWhichIsLargerThanMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new Maximum(10))->validate(11));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Maximum', $result->getFirstError()->getMessage());
        $this->assertSame('maximum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(10, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAFloatWhichIsLargerThanMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new Maximum(10))->validate(11.1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Maximum', $result->getFirstError()->getMessage());
        $this->assertSame('maximum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(10, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAnIntegerAsAStringWhichIsLargerThanMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new Maximum(10))->validate('11'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Maximum', $result->getFirstError()->getMessage());
        $this->assertSame('maximum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(10, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAFloatAsAStringWhichIsLargerThanMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new Maximum(10))->validate('11.1'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Maximum', $result->getFirstError()->getMessage());
        $this->assertSame('maximum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(10, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateSucceedWhenPassingAnIntegerWhichIsLessThanMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new Maximum(10))->validate(1));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedWhenPassingAnIntegerWhichIsExactlyMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new Maximum(10))->validate(10));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedWhenPassingAFloatWhichIsLessThanMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new Maximum(10))->validate(1.1));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedWhenPassingAFloatWhichIsExactlyMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new Maximum(10))->validate(10.0));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnIntegerAsAStringWhichIsSmallerThanMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new Maximum(10))->validate('1'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnIntegerAsAStringWhichIsExactlyThanMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new Maximum(10))->validate('10'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAFloatAsAStringWhichIsSmallerThanMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new Maximum(10))->validate('1.1'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAFloatAsAStringWhichIsExactlyThanMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new Maximum(10))->validate('10.0'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
