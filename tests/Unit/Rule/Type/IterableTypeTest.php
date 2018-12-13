<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Type;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\IterableType;
use function Amp\Promise\wait;

class IterableTypeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new IterableType());
    }

    public function testValidateFailsWhenPassingAnInteger(): void
    {
        /** @var Result $result */
        $result = wait((new IterableType())->validate(1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.IterableType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAFloat(): void
    {
        /** @var Result $result */
        $result = wait((new IterableType())->validate(1.1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.IterableType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingABoolean(): void
    {
        /** @var Result $result */
        $result = wait((new IterableType())->validate(true));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.IterableType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnObject(): void
    {
        /** @var Result $result */
        $result = wait((new IterableType())->validate(new \DateTimeImmutable()));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.IterableType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingNull(): void
    {
        /** @var Result $result */
        $result = wait((new IterableType())->validate(null));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.IterableType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        /** @var Result $result */
        $result = wait((new IterableType())->validate($resource));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.IterableType', $result->getFirstError()->getMessage());

        fclose($resource);
    }

    public function testValidateFailsWhenPassingACallable(): void
    {
        /** @var Result $result */
        $result = wait((new IterableType())->validate(static function (): void {
        }));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.IterableType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAString(): void
    {
        /** @var Result $result */
        $result = wait((new IterableType())->validate('€'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.IterableType', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAnArray(): void
    {
        /** @var Result $result */
        $result = wait((new IterableType())->validate([]));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
