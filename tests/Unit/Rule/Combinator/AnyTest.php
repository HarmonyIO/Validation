<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Combinator;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Combinator\Any;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Text\MaximumLength;
use HarmonyIO\Validation\Rule\Text\MinimumLength;

class AnyTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Any());
    }

    public function testValidateReturnsFalseWhenNoRulesAreAdded(): void
    {
        $this->assertFalse((new Any())->validate('Test value'));
    }

    public function testValidateReturnsTrueWhenBothRulesAreValid(): void
    {
        $this->assertTrue((new Any(
            new MinimumLength(3),
            new MaximumLength(15)
        ))->validate('Test value'));
    }

    public function testValidateReturnsTrueWhenFirstRuleIsInvalid(): void
    {
        $this->assertTrue((new Any(
            new MinimumLength(11),
            new MaximumLength(15)
        ))->validate('Test value'));
    }

    public function testValidateReturnsTrueWhenLastRuleIsInvalid(): void
    {
        $this->assertTrue((new Any(
            new MinimumLength(3),
            new MaximumLength(9)
        ))->validate('Test value'));
    }

    public function testValidateReturnsFalseWhenAllRulesAreInvalid(): void
    {
        $this->assertFalse((new Any(
            new MinimumLength(11),
            new MaximumLength(9)
        ))->validate('Test value'));
    }
}
