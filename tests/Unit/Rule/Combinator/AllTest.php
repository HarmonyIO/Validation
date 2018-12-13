<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Combinator;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Combinator\All;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Text\MaximumLength;
use HarmonyIO\Validation\Rule\Text\MinimumLength;
use function Amp\Promise\wait;

class AllTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new All());
    }

    public function testValidateSucceedsWhenNoRulesAreAdded(): void
    {
        /** @var Result $result */
        $result = wait((new All())->validate('Test value'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenBothRulesAreValid(): void
    {
        /** @var Result $result */
        $result = wait((new All(
            new MinimumLength(3),
            new MaximumLength(15)
        ))->validate('Test value'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateFailsWhenBothRulesAreInvalid(): void
    {
        /** @var Result $result */
        $result = wait((new All(
            new MaximumLength(9),
            new MaximumLength(9)
        ))->validate('Test value'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Text.MaximumLength', $result->getFirstError()->getMessage());
        $this->assertCount(2, $result->getErrors());
        $this->assertSame('Text.MaximumLength', $result->getErrors()[1]->getMessage());
    }

    public function testValidateFailsWhenFirstRuleIsInvalid(): void
    {
        /** @var Result $result */
        $result = wait((new All(
            new MinimumLength(11),
            new MaximumLength(15)
        ))->validate('Test value'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Text.MinimumLength', $result->getFirstError()->getMessage());
        $this->assertCount(1, $result->getErrors());
    }

    public function testValidateFailsWhenLastRuleIsInvalid(): void
    {
        /** @var Result $result */
        $result = wait((new All(
            new MinimumLength(3),
            new MaximumLength(9)
        ))->validate('Test value'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Text.MaximumLength', $result->getFirstError()->getMessage());
        $this->assertCount(1, $result->getErrors());
    }
}
