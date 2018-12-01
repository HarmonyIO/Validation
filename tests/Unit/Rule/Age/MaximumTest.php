<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Age;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Age\Maximum;
use HarmonyIO\Validation\Rule\Rule;

class MaximumTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Maximum(18));
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Maximum(18))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Maximum(18))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Maximum(18))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Maximum(18))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Maximum(18))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Maximum(18))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Maximum(18))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenPassingAString(): void
    {
        $this->assertFalse((new Maximum(18))->validate('1980-01-01'));
    }

    public function testValidateReturnsFalseWhenValueIsOlderThanMaximum(): void
    {
        // phpcs:ignore PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket
        $this->assertFalse((new Maximum(1))->validate(
            // phpcs:ignore PSR2.Methods.FunctionCallSignature.Indent,PSR2.Methods.FunctionCallSignature.CloseBracketLine
            (new \DateTimeImmutable())->sub(new \DateInterval('P1Y1D')))
        );
    }

    public function testValidateReturnsTrueWhenValueIsExactlyMaximum(): void
    {
        // phpcs:ignore PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket
        $this->assertTrue((new Maximum(1))->validate(
            // phpcs:ignore PSR2.Methods.FunctionCallSignature.Indent,PSR2.Methods.FunctionCallSignature.CloseBracketLine
            (new \DateTimeImmutable())->sub(new \DateInterval('P1Y')))
        );
    }

    public function testValidateReturnsTrueWhenValueIsYoungerThanMaximum(): void
    {
        // phpcs:ignore PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket
        $this->assertTrue((new Maximum(1))->validate(
            // phpcs:ignore PSR2.Methods.FunctionCallSignature.Indent,PSR2.Methods.FunctionCallSignature.CloseBracketLine
            (new \DateTimeImmutable())->sub(new \DateInterval('P1Y'))->add(new \DateInterval('P1D')))
        );
    }
}
