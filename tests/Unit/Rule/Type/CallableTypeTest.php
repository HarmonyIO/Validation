<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Type;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\CallableType;
use function Amp\Promise\wait;

class CallableTypeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new CallableType());
    }

    public function testValidateFailsWhenPassingAnInteger(): void
    {
        /** @var Result $result */
        $result = wait((new CallableType())->validate(1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.CallableType', $result->getFirstError()->getMessage());
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        /** @var Result $result */
        $result = wait((new CallableType())->validate(1.1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.CallableType', $result->getFirstError()->getMessage());
    }

    public function testValidateReturnsTrueWhenPassingABoolean(): void
    {
        /** @var Result $result */
        $result = wait((new CallableType())->validate(true));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.CallableType', $result->getFirstError()->getMessage());
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        /** @var Result $result */
        $result = wait((new CallableType())->validate([]));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.CallableType', $result->getFirstError()->getMessage());
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        /** @var Result $result */
        $result = wait((new CallableType())->validate(new \DateTimeImmutable()));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.CallableType', $result->getFirstError()->getMessage());
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        /** @var Result $result */
        $result = wait((new CallableType())->validate(null));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.CallableType', $result->getFirstError()->getMessage());
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        /** @var Result $result */
        $result = wait((new CallableType())->validate($resource));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.CallableType', $result->getFirstError()->getMessage());

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingAString(): void
    {
        /** @var Result $result */
        $result = wait((new CallableType())->validate('€'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.CallableType', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingACallable(): void
    {
        /** @var Result $result */
        $result = wait((new CallableType())->validate(static function (): void {
        }));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
