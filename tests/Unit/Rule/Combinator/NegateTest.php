<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Combinator;

use Amp\Success;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Combinator\Negate;
use HarmonyIO\Validation\Rule\Rule;
use PHPUnit\Framework\MockObject\MockObject;

class NegateTest extends TestCase
{
    /** @var MockObject|Rule */
    private $rule;

    public function setUp()
    {
        $this->rule = $this->createMock(Rule::class);

        $this->rule
            ->method('validate')
            ->willReturnCallback(function(bool $value) {
                return new Success($value);
            })
        ;
    }

    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Negate($this->rule));
    }

    public function testValidateReturnsFalseWhenRuleReturnsTrue(): void
    {
        $this->assertFalse((new Negate($this->rule))->validate(true));
    }

    public function testValidateReturnsTrueWhenRuleReturnsFalse(): void
    {
        $this->assertTrue((new Negate($this->rule))->validate(false));
    }
}
