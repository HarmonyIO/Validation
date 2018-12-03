<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\GeoLocation;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Exception\InvalidLongitude;
use HarmonyIO\Validation\Exception\InvalidNumericalRange;
use HarmonyIO\Validation\Rule\GeoLocation\LongitudeRange;
use HarmonyIO\Validation\Rule\Rule;

class LongitudeRangeTest extends TestCase
{
    public function testConstructorThrowsWhenMinimumValueIsGreaterThanMaximumValue(): void
    {
        $this->expectException(InvalidNumericalRange::class);
        $this->expectExceptionMessage('The minimum (`51`) can not be greater than the maximum (`50`).');

        new LongitudeRange(51, 50);
    }

    public function testConstructorThrowsOnOutOfRangeMinimumValueTooLow(): void
    {
        $this->expectException(InvalidLongitude::class);
        $this->expectExceptionMessage('Provided longitude (`-180`) must be within range -180 to 180 (exclusive).');

        new LongitudeRange(-180, 50);
    }

    public function testConstructorThrowsOnOutOfRangeMinimumValueTooHigh(): void
    {
        $this->expectException(InvalidLongitude::class);
        $this->expectExceptionMessage('Provided longitude (`180`) must be within range -180 to 180 (exclusive).');

        new LongitudeRange(180, 180);
    }

    public function testConstructorThrowsOnOutOfRangeMaximumValueTooLow(): void
    {
        $this->expectException(InvalidLongitude::class);
        $this->expectExceptionMessage('Provided longitude (`-180`) must be within range -180 to 180 (exclusive).');

        new LongitudeRange(-180, -180);
    }

    public function testConstructorThrowsOnOutOfRangeMaximumValueTooHigh(): void
    {
        $this->expectException(InvalidLongitude::class);
        $this->expectExceptionMessage('Provided longitude (`180`) must be within range -180 to 180 (exclusive).');

        new LongitudeRange(50, 180);
    }

    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new LongitudeRange(-179.99999, 179.99999));
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerJustAboveThreshold(): void
    {
        $this->assertTrue((new LongitudeRange(-179.99999, 179.99999))->validate(-179));
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerJustBelowThreshold(): void
    {
        $this->assertTrue((new LongitudeRange(-179.99999, 179.99999))->validate(179));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerAboveThreshold(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate(181));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerBelowThreshold(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate(-181));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerMatchingLowerBound(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate(-180));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerMatchingHigherBound(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate(180));
    }

    public function testValidateReturnsTrueWhenPassingAFloatJustAboveThreshold(): void
    {
        $this->assertTrue((new LongitudeRange(-179.99999, 179.99999))->validate(-179.9999));
    }

    public function testValidateReturnsTrueWhenPassingAFloatJustBelowThreshold(): void
    {
        $this->assertTrue((new LongitudeRange(-179.99999, 179.99999))->validate(179.9999));
    }

    public function testValidateReturnsFalseWhenPassingAFloatAboveThreshold(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate(180.0001));
    }

    public function testValidateReturnsFalseWhenPassingAFloatBelowThreshold(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate(-180.0001));
    }

    public function testValidateReturnsFalseWhenPassingAFloatMatchingLowerBound(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate(-180.0000));
    }

    public function testValidateReturnsFalseWhenPassingAFloatMatchingHigherBound(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate(180.0000));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsTrueWhenPassingAStringIntegerJustAboveThreshold(): void
    {
        $this->assertTrue((new LongitudeRange(-179.99999, 179.99999))->validate('-179'));
    }

    public function testValidateReturnsTrueWhenPassingAStringIntegerJustBelowThreshold(): void
    {
        $this->assertTrue((new LongitudeRange(-179.99999, 179.99999))->validate('179'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerAboveThreshold(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate('181'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerBelowThreshold(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate('-181'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerMatchingLowerBound(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate('-180'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerMatchingHigherBound(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate('180'));
    }

    public function testValidateReturnsTrueWhenPassingAStringFloatJustAboveThreshold(): void
    {
        $this->assertTrue((new LongitudeRange(-179.99999, 179.99999))->validate('-179.9999'));
    }

    public function testValidateReturnsTrueWhenPassingAStringFloatJustBelowThreshold(): void
    {
        $this->assertTrue((new LongitudeRange(-179.99999, 179.99999))->validate('179.9999'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatAboveThreshold(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate('180.0001'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatBelowThreshold(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate('-180.0001'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatMatchingLowerBound(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate('-180.0000'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatMatchingHigherBound(): void
    {
        $this->assertFalse((new LongitudeRange(-179.99999, 179.99999))->validate('180.0000'));
    }

    public function testValidateReturnsTrueWhenPassingAStringIntegerJustAboveMinimumValue(): void
    {
        $this->assertTrue((new LongitudeRange(60, 80))->validate('61'));
    }

    public function testValidateReturnsTrueWhenPassingAStringIntegerJustBelowMaximumValue(): void
    {
        $this->assertTrue((new LongitudeRange(60, 80))->validate('79'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerAboveMaximumValue(): void
    {
        $this->assertFalse((new LongitudeRange(60, 80))->validate('81'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerBelowMinimumValue(): void
    {
        $this->assertFalse((new LongitudeRange(60, 80))->validate('59'));
    }

    public function testValidateReturnsTrueWhenPassingAStringIntegerMatchingMinimumValue(): void
    {
        $this->assertTrue((new LongitudeRange(60, 80))->validate('60'));
    }

    public function testValidateReturnsTrueWhenPassingAStringIntegerMatchingMaximumValue(): void
    {
        $this->assertTrue((new LongitudeRange(60, 80))->validate('80'));
    }

    public function testValidateReturnsTrueWhenPassingAStringFloatJustAboveMinimumValue(): void
    {
        $this->assertTrue((new LongitudeRange(60.555, 80.334))->validate('60.556'));
    }

    public function testValidateReturnsTrueWhenPassingAStringFloatJustBelowMaximumValue(): void
    {
        $this->assertTrue((new LongitudeRange(60.555, 80.334))->validate('80.333'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatBelowMinimumValue(): void
    {
        $this->assertFalse((new LongitudeRange(60.555, 80.334))->validate('60.554'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatAboveMaximumValue(): void
    {
        $this->assertFalse((new LongitudeRange(60.555, 80.334))->validate('80.335'));
    }

    public function testValidateReturnsTrueWhenPassingAStringFloatMatchingMinimumValue(): void
    {
        $this->assertTrue((new LongitudeRange(60.555, 80.334))->validate('60.555'));
    }

    public function testValidateReturnsTrueWhenPassingAStringFloatMatchingMaximumValue(): void
    {
        $this->assertTrue((new LongitudeRange(60.555, 80.334))->validate('80.334'));
    }
}
