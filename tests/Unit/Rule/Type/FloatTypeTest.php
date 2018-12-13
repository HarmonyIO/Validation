<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Type;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\FloatType;
use function Amp\Promise\wait;

class FloatTypeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new FloatType());
    }

    public function testValidateFailsWhenPassingAnInteger(): void
    {
        /** @var Result $result */
        $result = wait((new FloatType())->validate(1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.FloatType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingABoolean(): void
    {
        /** @var Result $result */
        $result = wait((new FloatType())->validate(true));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.FloatType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnArray(): void
    {
        /** @var Result $result */
        $result = wait((new FloatType())->validate([]));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.FloatType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnObject(): void
    {
        /** @var Result $result */
        $result = wait((new FloatType())->validate(new \DateTimeImmutable()));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.FloatType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingNull(): void
    {
        /** @var Result $result */
        $result = wait((new FloatType())->validate(null));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.FloatType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        /** @var Result $result */
        $result = wait((new FloatType())->validate($resource));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.FloatType', $result->getFirstError()->getMessage());

        fclose($resource);
    }

    public function testValidateFailsWhenPassingACallable(): void
    {
        /** @var Result $result */
        $result = wait((new FloatType())->validate(static function (): void {
        }));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.FloatType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAString(): void
    {
        /** @var Result $result */
        $result = wait((new FloatType())->validate('€'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.FloatType', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAFloat(): void
    {
        /** @var Result $result */
        $result = wait((new FloatType())->validate(1.1));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
