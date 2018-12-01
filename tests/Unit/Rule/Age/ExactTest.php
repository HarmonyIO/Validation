<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Age;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Age\Exact;
use HarmonyIO\Validation\Rule\Rule;

class ExactTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Exact(18));
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Exact(18))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Exact(18))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Exact(18))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Exact(18))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Exact(18))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Exact(18))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Exact(18))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenPassingAString(): void
    {
        $this->assertFalse((new Exact(18))->validate('1980-01-01'));
    }

    public function testValidateReturnsFalseWhenValueIsYoungerThanRequiredAge(): void
    {
        // phpcs:ignore PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket
        $this->assertFalse((new Exact(1))->validate(
            // phpcs:ignore PSR2.Methods.FunctionCallSignature.Indent,PSR2.Methods.FunctionCallSignature.CloseBracketLine
            (new \DateTimeImmutable())->sub(new \DateInterval('P1Y'))->add(new \DateInterval('P1D')))
        );
    }

    public function testValidateReturnsFalseWhenValueIsOlderThanRequiredAge(): void
    {
        // phpcs:ignore PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket
        $this->assertFalse((new Exact(1))->validate(
            // phpcs:ignore PSR2.Methods.FunctionCallSignature.Indent,PSR2.Methods.FunctionCallSignature.CloseBracketLine
            (new \DateTimeImmutable())->sub(new \DateInterval('P2Y1D')))
        );
    }

    public function testValidateReturnsTrueWhenValueIsExactlyRequiredAge(): void
    {
        // phpcs:ignore PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket
        $this->assertTrue((new Exact(1))->validate(
            // phpcs:ignore PSR2.Methods.FunctionCallSignature.Indent,PSR2.Methods.FunctionCallSignature.CloseBracketLine
            (new \DateTimeImmutable())->sub(new \DateInterval('P1Y')))
        );
    }
}
