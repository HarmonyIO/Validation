<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Type;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\ResourceType;
use function Amp\Promise\wait;

class ResourceTypeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new ResourceType());
    }

    public function testValidateFailsWhenPassingAnInteger(): void
    {
        /** @var Result $result */
        $result = wait((new ResourceType())->validate(1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.ResourceType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAFloat(): void
    {
        /** @var Result $result */
        $result = wait((new ResourceType())->validate(1.1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.ResourceType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingABoolean(): void
    {
        /** @var Result $result */
        $result = wait((new ResourceType())->validate(true));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.ResourceType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnArray(): void
    {
        /** @var Result $result */
        $result = wait((new ResourceType())->validate([]));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.ResourceType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnObject(): void
    {
        /** @var Result $result */
        $result = wait((new ResourceType())->validate(new \DateTimeImmutable()));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.ResourceType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingNull(): void
    {
        /** @var Result $result */
        $result = wait((new ResourceType())->validate(null));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.ResourceType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingACallable(): void
    {
        /** @var Result $result */
        $result = wait((new ResourceType())->validate(static function (): void {
        }));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.ResourceType', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAString(): void
    {
        /** @var Result $result */
        $result = wait((new ResourceType())->validate('€'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.ResourceType', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        /** @var Result $result */
        $result = wait((new ResourceType())->validate($resource));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());

        fclose($resource);
    }
}
