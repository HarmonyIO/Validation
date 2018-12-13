<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Type;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\BooleanType;
use function Amp\Promise\wait;

class BooleanTypeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new BooleanType());
    }

    public function testValidateFailsWhenPassingAnInteger(): void
    {
        /** @var Result $result */
        $result = wait((new BooleanType())->validate(1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.BooleanType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAFloat(): void
    {
        /** @var Result $result */
        $result = wait((new BooleanType())->validate(1.1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.BooleanType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnArray(): void
    {
        /** @var Result $result */
        $result = wait((new BooleanType())->validate([]));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.BooleanType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnObject(): void
    {
        /** @var Result $result */
        $result = wait((new BooleanType())->validate(new \DateTimeImmutable()));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.BooleanType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingNull(): void
    {
        /** @var Result $result */
        $result = wait((new BooleanType())->validate(null));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.BooleanType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        /** @var Result $result */
        $result = wait((new BooleanType())->validate($resource));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.BooleanType', $result->getFirstError()->getMessage());

        fclose($resource);
    }

    public function testValidateFailsWhenPassingACallable(): void
    {
        /** @var Result $result */
        $result = wait((new BooleanType())->validate(static function (): void {
        }));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.BooleanType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAString(): void
    {
        /** @var Result $result */
        $result = wait((new BooleanType())->validate('€'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.BooleanType', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingABoolean(): void
    {
        /** @var Result $result */
        $result = wait((new BooleanType())->validate(true));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
