<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Combinator;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Combinator\All;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Text\MaximumLength;
use HarmonyIO\Validation\Rule\Text\MinimumLength;

class AllTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new All());
    }

    public function testValidateReturnsTrueWhenNoRulesAreAdded(): void
    {
        $this->assertTrue((new All())->validate('Test value'));
    }

    public function testValidateReturnsTrueWhenBothRulesAreValid(): void
    {
        $this->assertTrue((new All(
            new MinimumLength(3),
            new MaximumLength(15)
        ))->validate('Test value'));
    }

    public function testValidateReturnsFalseWhenFirstRuleIsInvalid(): void
    {
        $this->assertFalse((new All(
            new MinimumLength(11),
            new MaximumLength(15)
        ))->validate('Test value'));
    }

    public function testValidateReturnsTrueWhenLastRuleIsInvalid(): void
    {
        $this->assertFalse((new All(
            new MinimumLength(3),
            new MaximumLength(9)
        ))->validate('Test value'));
    }
}
