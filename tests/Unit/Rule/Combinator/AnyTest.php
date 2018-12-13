<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Combinator;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Combinator\All;
use HarmonyIO\Validation\Rule\Combinator\Any;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Text\MaximumLength;
use HarmonyIO\Validation\Rule\Text\MinimumLength;
use function Amp\Promise\wait;

class AnyTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Any());
    }

    public function testValidateFailsWhenAllRulesAreInvalid(): void
    {
        /** @var Result $result */
        $result = wait((new Any(
            new MinimumLength(11),
            new MaximumLength(9)
        ))->validate('Test value'));

        $this->assertFalse($result->isValid());
        $this->assertCount(2, $result->getErrors());
        $this->assertSame('Text.MinimumLength', $result->getFirstError()->getMessage());
        $this->assertSame('Text.MaximumLength', $result->getErrors()[1]->getMessage());
    }

    public function testValidateSucceedsWhenNoRulesAreAdded(): void
    {
        /** @var Result $result */
        $result = wait((new Any())->validate('Test value'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenBothRulesAreValid(): void
    {
        /** @var Result $result */
        $result = wait((new Any(
            new MinimumLength(3),
            new MaximumLength(15)
        ))->validate('Test value'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenFirstRuleIsValid(): void
    {
        /** @var Result $result */
        $result = wait((new Any(
            new MinimumLength(11),
            new MaximumLength(15)
        ))->validate('Test value'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenLastRuleIsValid(): void
    {
        /** @var Result $result */
        $result = wait((new Any(
            new MinimumLength(3),
            new MaximumLength(9)
        ))->validate('Test value'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenRulesContainAllRuleWithMoreErrorsThanAnyRules(): void
    {
        /** @var Result $result */
        $result = wait((new Any(new All(
            new MaximumLength(3),
            new MaximumLength(3),
            new MaximumLength(3)
        ), new MinimumLength(3)))->validate('Test value'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
