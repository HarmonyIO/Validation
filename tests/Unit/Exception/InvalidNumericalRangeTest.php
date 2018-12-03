<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Exception;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Exception\InvalidNumericalRange;
use HarmonyIO\Validation\Exception\InvalidNumericValue;

class InvalidNumericalRangeTest extends TestCase
{
    public function testConstructorThrowsUpWhenPassingANonNumericalMinimumValue(): void
    {
        $this->expectException(InvalidNumericValue::class);
        $this->expectExceptionMessage('Value (`not a numerical value`) must be a numeric value.');

        new InvalidNumericalRange('not a numerical value', 18);
    }

    public function testConstructorThrowsUpWhenPassingANonNumericalMaximumValue(): void
    {
        $this->expectException(InvalidNumericValue::class);
        $this->expectExceptionMessage('Value (`not a numerical value`) must be a numeric value.');

        new InvalidNumericalRange(18, 'not a numerical value');
    }

    public function testMessageIsFormattedCorrectly(): void
    {
        $typeException = new InvalidNumericalRange(21, 18);

        $this->assertSame(
            'The minimum (`21`) can not be greater than the maximum (`18`).',
            $typeException->getMessage()
        );
    }
}
