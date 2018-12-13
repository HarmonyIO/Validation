<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Type;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\IntegerType;
use function Amp\Promise\wait;

class IntegerTypeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new IntegerType());
    }

    public function testValidateFailsWhenPassingAFloat(): void
    {
        /** @var Result $result */
        $result = wait((new IntegerType())->validate(1.1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.IntegerType', $result->getFirstError()->getMessage());
    }

    public function testValidateReturnsTrueWhenPassingABoolean(): void
    {
        /** @var Result $result */
        $result = wait((new IntegerType())->validate(true));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.IntegerType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnArray(): void
    {
        /** @var Result $result */
        $result = wait((new IntegerType())->validate([]));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.IntegerType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnObject(): void
    {
        /** @var Result $result */
        $result = wait((new IntegerType())->validate(new \DateTimeImmutable()));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.IntegerType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingNull(): void
    {
        /** @var Result $result */
        $result = wait((new IntegerType())->validate(null));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.IntegerType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        /** @var Result $result */
        $result = wait((new IntegerType())->validate($resource));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.IntegerType', $result->getFirstError()->getMessage());

        fclose($resource);
    }

    public function testValidateFailsWhenPassingACallable(): void
    {
        /** @var Result $result */
        $result = wait((new IntegerType())->validate(static function (): void {
        }));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.IntegerType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAString(): void
    {
        /** @var Result $result */
        $result = wait((new IntegerType())->validate('€'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.IntegerType', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAnInteger(): void
    {
        /** @var Result $result */
        $result = wait((new IntegerType())->validate(1));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
