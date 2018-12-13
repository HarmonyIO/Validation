<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Type;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\NullType;
use function Amp\Promise\wait;

class NullTypeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new NullType());
    }

    public function testValidateFailsWhenPassingAnInteger(): void
    {
        /** @var Result $result */
        $result = wait((new NullType())->validate(1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.NullType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAFloat(): void
    {
        /** @var Result $result */
        $result = wait((new NullType())->validate(1.1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.NullType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingABoolean(): void
    {
        /** @var Result $result */
        $result = wait((new NullType())->validate(true));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.NullType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnArray(): void
    {
        /** @var Result $result */
        $result = wait((new NullType())->validate([]));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.NullType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnObject(): void
    {
        /** @var Result $result */
        $result = wait((new NullType())->validate(new \DateTimeImmutable()));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.NullType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        /** @var Result $result */
        $result = wait((new NullType())->validate($resource));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.NullType', $result->getFirstError()->getMessage());

        fclose($resource);
    }

    public function testValidateFailsWhenPassingACallable(): void
    {
        /** @var Result $result */
        $result = wait((new NullType())->validate(static function (): void {
        }));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.NullType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAString(): void
    {
        /** @var Result $result */
        $result = wait((new NullType())->validate('€'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.NullType', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingNull(): void
    {
        /** @var Result $result */
        $result = wait((new NullType())->validate(null));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
