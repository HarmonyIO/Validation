<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Age;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Exception\InvalidAgeRange;
use HarmonyIO\Validation\Rule\Age\Range;
use HarmonyIO\Validation\Rule\Rule;

class RangeTest extends TestCase
{
    public function testConstructorThrowsOnInvalidRange(): void
    {
        $this->expectException(InvalidAgeRange::class);
        $this->expectExceptionMessage('The minimum age (`21`) can not be greater than the maximum age (`18`).');

        new Range(21, 18);
    }

    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Range(18, 21));
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Range(18, 21))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Range(18, 21))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Range(18, 21))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Range(18, 21))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Range(18, 21))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Range(18, 21))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Range(18, 21))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenPassingAString(): void
    {
        $this->assertFalse((new Range(18, 21))->validate('1980-01-01'));
    }

    public function testValidateReturnsFalseWhenValueIsYoungerThanMinimumAge(): void
    {
        // phpcs:ignore PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket
        $this->assertFalse((new Range(1, 2))->validate(
            // phpcs:ignore PSR2.Methods.FunctionCallSignature.Indent,PSR2.Methods.FunctionCallSignature.CloseBracketLine
            (new \DateTimeImmutable())->sub(new \DateInterval('P1Y'))->add(new \DateInterval('P1D')))
        );
    }

    public function testValidateReturnsFalseWhenValueIsOlderThanMaximumAge(): void
    {
        // phpcs:ignore PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket
        $this->assertFalse((new Range(1, 2))->validate(
            // phpcs:ignore PSR2.Methods.FunctionCallSignature.Indent,PSR2.Methods.FunctionCallSignature.CloseBracketLine
            (new \DateTimeImmutable())->sub(new \DateInterval('P3Y1D')))
        );
    }

    public function testValidateReturnsTrueWhenValueIsExactlyMinimumAge(): void
    {
        // phpcs:ignore PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket
        $this->assertTrue((new Range(1, 2))->validate(
            // phpcs:ignore PSR2.Methods.FunctionCallSignature.Indent,PSR2.Methods.FunctionCallSignature.CloseBracketLine
            (new \DateTimeImmutable())->sub(new \DateInterval('P1Y')))
        );
    }

    public function testValidateReturnsTrueWhenValueIsExactlyMaximumAge(): void
    {
        // phpcs:ignore PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket
        $this->assertTrue((new Range(1, 2))->validate(
            // phpcs:ignore PSR2.Methods.FunctionCallSignature.Indent,PSR2.Methods.FunctionCallSignature.CloseBracketLine
            (new \DateTimeImmutable())->sub(new \DateInterval('P1Y')))
        );
    }

    public function testValidateReturnsTrueWhenValueIsInRange(): void
    {
        // phpcs:ignore PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket
        $this->assertTrue((new Range(1, 2))->validate(
            // phpcs:ignore PSR2.Methods.FunctionCallSignature.Indent,PSR2.Methods.FunctionCallSignature.CloseBracketLine
            (new \DateTimeImmutable())->sub(new \DateInterval('P1Y6M')))
        );
    }
}
