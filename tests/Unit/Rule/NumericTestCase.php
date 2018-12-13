<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\Promise\wait;

class NumericTestCase extends TestCase
{
    /** @var string */
    protected $classUnderTest;

    /** @var mixed[] */
    protected $parameters = [];

    /** @var Rule */
    protected $testObject;

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

    public function testValidateFailsWhenPassingABoolean(): void
    {
        /** @var Result $result */
        $result = wait($this->testObject->validate(true));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.NumericType', $result->getErrors()[0]->getMessage());
    }

    public function testValidateFailsWhenPassingAnArray(): void
    {
        /** @var Result $result */
        $result = wait($this->testObject->validate([]));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.NumericType', $result->getErrors()[0]->getMessage());
    }

    public function testValidateFailsWhenPassingNull(): void
    {
        /** @var Result $result */
        $result = wait($this->testObject->validate(null));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.NumericType', $result->getErrors()[0]->getMessage());
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
        $this->assertSame('Numeric.NumericType', $result->getErrors()[0]->getMessage());

        fclose($resource);
    }

    public function testValidateFailsWhenPassingACallable(): void
    {
        /** @var Result $result */
        $result = wait($this->testObject->validate(static function (): void {
        }));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.NumericType', $result->getErrors()[0]->getMessage());
    }

    public function testValidateFailsWhenPassingAnObject(): void
    {
        /** @var Result $result */
        $result = wait($this->testObject->validate(new \DateTimeImmutable()));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.NumericType', $result->getErrors()[0]->getMessage());
    }

    public function testValidateFailsWhenPassingANonNumericString(): void
    {
        /** @var Result $result */
        $result = wait($this->testObject->validate('non numeric'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.NumericType', $result->getErrors()[0]->getMessage());
    }
}
