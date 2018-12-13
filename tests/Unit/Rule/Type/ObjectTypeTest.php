<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Type;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\ObjectType;
use function Amp\Promise\wait;

class ObjectTypeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new ObjectType());
    }

    public function testValidateFailsWhenPassingAnInteger(): void
    {
        /** @var Result $result */
        $result = wait((new ObjectType())->validate(1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.ObjectType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAFloat(): void
    {
        /** @var Result $result */
        $result = wait((new ObjectType())->validate(1.1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.ObjectType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingABoolean(): void
    {
        /** @var Result $result */
        $result = wait((new ObjectType())->validate(true));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.ObjectType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnArray(): void
    {
        /** @var Result $result */
        $result = wait((new ObjectType())->validate([]));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.ObjectType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingNull(): void
    {
        /** @var Result $result */
        $result = wait((new ObjectType())->validate(null));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.ObjectType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        /** @var Result $result */
        $result = wait((new ObjectType())->validate($resource));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.ObjectType', $result->getFirstError()->getMessage());

        fclose($resource);
    }

    public function testValidateFailsWhenPassingAString(): void
    {
        /** @var Result $result */
        $result = wait((new ObjectType())->validate('€'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.ObjectType', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingACallable(): void
    {
        /** @var Result $result */
        $result = wait((new ObjectType())->validate(static function (): void {
        }));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnObject(): void
    {
        /** @var Result $result */
        $result = wait((new ObjectType())->validate(new \DateTimeImmutable()));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
