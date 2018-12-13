<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Combinator;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Combinator\AtLeast;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Text\MaximumLength;
use HarmonyIO\Validation\Rule\Text\MinimumLength;
use function Amp\Promise\wait;

class AtLeastTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new AtLeast(0));
    }

    public function testValidateSucceedsWhenNoRulesAreAdded(): void
    {
        /** @var Result $result */
        $result = wait((new AtLeast(0))->validate('Test value'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateFailsWhenUnderTheMinimumOfValidRules(): void
    {
        /** @var Result $result */
        $result = wait((new AtLeast(
            3,
            new MinimumLength(11),
            new MaximumLength(15)
        ))->validate('Test value'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Text.MinimumLength', $result->getFirstError()->getMessage());
        $this->assertCount(1, $result->getErrors());
    }

    public function testValidateSucceedsWhenOverTheMinimumOfValidRules(): void
    {
        /** @var Result $result */
        $result = wait((new AtLeast(
            1,
            new MinimumLength(3),
            new MaximumLength(15)
        ))->validate('Test value'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenExactlyTheMinimumOfValidRules(): void
    {
        /** @var Result $result */
        $result = wait((new AtLeast(
            2,
            new MinimumLength(3),
            new MaximumLength(15)
        ))->validate('Test value'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
