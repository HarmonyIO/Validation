<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Numeric;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Numeric\Integer;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\Promise\wait;

class IntegerTest extends TestCase
{
    /**
     * @param mixed[] $data
     */
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Integer());
    }

    public function testValidateFailsWhenPassingAFloat(): void
    {
        /** @var Result $result */
        $result = wait((new Integer())->validate(1.1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Integer', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingABoolean(): void
    {
        /** @var Result $result */
        $result = wait((new Integer())->validate(true));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Integer', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnArray(): void
    {
        /** @var Result $result */
        $result = wait((new Integer())->validate([]));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Integer', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnObject(): void
    {
        /** @var Result $result */
        $result = wait((new Integer())->validate(new \DateTimeImmutable()));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Integer', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingNull(): void
    {
        /** @var Result $result */
        $result = wait((new Integer())->validate(null));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Integer', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        /** @var Result $result */
        $result = wait((new Integer())->validate($resource));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Integer', $result->getFirstError()->getMessage());

        fclose($resource);
    }

    public function testValidateFailsWhenPassingACallable(): void
    {
        /** @var Result $result */
        $result = wait((new Integer())->validate(static function (): void {
        }));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Integer', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAFloatAsAString(): void
    {
        /** @var Result $result */
        $result = wait((new Integer())->validate('1.1'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Integer', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAnIntegerAsAString(): void
    {
        /** @var Result $result */
        $result = wait((new Integer())->validate('1'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnInteger(): void
    {
        /** @var Result $result */
        $result = wait((new Integer())->validate(1));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
