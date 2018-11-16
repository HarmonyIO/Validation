<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Combinator;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Combinator\AtLeast;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Text\MaximumLength;
use HarmonyIO\Validation\Rule\Text\MinimumLength;

class AtLeastTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new AtLeast(0));
    }

    public function testValidateReturnsTrueWhenNoRulesAreAdded(): void
    {
        $this->assertTrue((new AtLeast(0))->validate('Test value'));
    }

    public function testValidateReturnsTrueWhenOverTheMinimumOfValidRules(): void
    {
        $this->assertTrue((new AtLeast(
            1,
            new MinimumLength(3),
            new MaximumLength(15)
        ))->validate('Test value'));
    }

    public function testValidateReturnsTrueWhenExactlyTheMinimumOfValidRules(): void
    {
        $this->assertTrue((new AtLeast(
            2,
            new MinimumLength(3),
            new MaximumLength(15)
        ))->validate('Test value'));
    }

    public function testValidateReturnsFalseWhenUnderTheMinimumOfValidRules(): void
    {
        $this->assertFalse((new AtLeast(
            3,
            new MinimumLength(11),
            new MaximumLength(15)
        ))->validate('Test value'));
    }
}
