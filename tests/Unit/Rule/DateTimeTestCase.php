<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\Promise\wait;

class DateTimeTestCase extends TestCase
{
    /** @var string */
    private $classUnderTest;

    /** @var mixed[] */
    private $parameters = [];

    /** @var Rule */
    private $testObject;

    /**
     * @param mixed[] $data
     * @param mixed ...$parameters
     */
    public function __construct(
        ?string $name,
        array $data,
        string $dataName,
        string $classUnderTest,
        ...$parameters
    ) {
        $this->classUnderTest = $classUnderTest;
        $this->parameters     = $parameters;

        parent::__construct($name, $data, $dataName);
    }

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $className = $this->classUnderTest;

        $this->testObject = new $className(...$this->parameters);
    }

    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, $this->testObject);
    }

    public function testValidateFailsWhenPassingAnInteger(): void
    {
        /** @var Result $result */
        $result = wait($this->testObject->validate(1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.InstanceOfType', $result->getErrors()[0]->getMessage());
        $this->assertSame('type', $result->getErrors()[0]->getParameters()[0]->getKey());
        $this->assertSame('integer', $result->getErrors()[0]->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAFloat(): void
    {
        /** @var Result $result */
        $result = wait($this->testObject->validate(1.1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.InstanceOfType', $result->getErrors()[0]->getMessage());
        $this->assertSame('type', $result->getErrors()[0]->getParameters()[0]->getKey());
        $this->assertSame('double', $result->getErrors()[0]->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingABoolean(): void
    {
        /** @var Result $result */
        $result = wait($this->testObject->validate(true));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.InstanceOfType', $result->getErrors()[0]->getMessage());
        $this->assertSame('type', $result->getErrors()[0]->getParameters()[0]->getKey());
        $this->assertSame('boolean', $result->getErrors()[0]->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAnArray(): void
    {
        /** @var Result $result */
        $result = wait($this->testObject->validate([]));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.InstanceOfType', $result->getErrors()[0]->getMessage());
        $this->assertSame('type', $result->getErrors()[0]->getParameters()[0]->getKey());
        $this->assertSame('array', $result->getErrors()[0]->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingNull(): void
    {
        /** @var Result $result */
        $result = wait($this->testObject->validate(null));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.InstanceOfType', $result->getErrors()[0]->getMessage());
        $this->assertSame('type', $result->getErrors()[0]->getParameters()[0]->getKey());
        $this->assertSame('NULL', $result->getErrors()[0]->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        /** @var Result $result */
        $result = wait($this->testObject->validate($resource));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.InstanceOfType', $result->getErrors()[0]->getMessage());
        $this->assertSame('type', $result->getErrors()[0]->getParameters()[0]->getKey());
        $this->assertSame('resource', $result->getErrors()[0]->getParameters()[0]->getValue());

        fclose($resource);
    }

    public function testValidateFailsWhenPassingACallable(): void
    {
        /** @var Result $result */
        $result = wait($this->testObject->validate(static function (): void {
        }));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.InstanceOfType', $result->getErrors()[0]->getMessage());
        $this->assertSame('type', $result->getErrors()[0]->getParameters()[0]->getKey());
        $this->assertSame('Closure', $result->getErrors()[0]->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAString(): void
    {
        /** @var Result $result */
        $result = wait($this->testObject->validate('1980-01-01'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Type.InstanceOfType', $result->getErrors()[0]->getMessage());
        $this->assertSame('type', $result->getErrors()[0]->getParameters()[0]->getKey());
        $this->assertSame('string', $result->getErrors()[0]->getParameters()[0]->getValue());
    }
}
