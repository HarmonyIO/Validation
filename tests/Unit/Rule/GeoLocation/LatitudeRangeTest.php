<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\GeoLocation;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Exception\InvalidLatitude;
use HarmonyIO\Validation\Exception\InvalidNumericalRange;
use HarmonyIO\Validation\Rule\GeoLocation\LatitudeRange;
use HarmonyIO\Validation\Rule\Rule;

class LatitudeRangeTest extends TestCase
{
    public function testConstructorThrowsWhenMinimumValueIsGreaterThanMaximumValue(): void
    {
        $this->expectException(InvalidNumericalRange::class);
        $this->expectExceptionMessage('The minimum (`51`) can not be greater than the maximum (`50`).');

        new LatitudeRange(51, 50);
    }

    public function testConstructorThrowsOnOutOfRangeMinimumValueTooLow(): void
    {
        $this->expectException(InvalidLatitude::class);
        $this->expectExceptionMessage('Provided latitude (`-90`) must be within range -90 to 90 (exclusive).');

        new LatitudeRange(-90, 50);
    }

    public function testConstructorThrowsOnOutOfRangeMinimumValueTooHigh(): void
    {
        $this->expectException(InvalidLatitude::class);
        $this->expectExceptionMessage('Provided latitude (`90`) must be within range -90 to 90 (exclusive).');

        new LatitudeRange(90, 90);
    }

    public function testConstructorThrowsOnOutOfRangeMaximumValueTooLow(): void
    {
        $this->expectException(InvalidLatitude::class);
        $this->expectExceptionMessage('Provided latitude (`-90`) must be within range -90 to 90 (exclusive).');

        new LatitudeRange(-90, -90);
    }

    public function testConstructorThrowsOnOutOfRangeMaximumValueTooHigh(): void
    {
        $this->expectException(InvalidLatitude::class);
        $this->expectExceptionMessage('Provided latitude (`90`) must be within range -90 to 90 (exclusive).');

        new LatitudeRange(50, 90);
    }

    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new LatitudeRange(-89.99999, 89.99999));
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerJustAboveThreshold(): void
    {
        $this->assertTrue((new LatitudeRange(-89.99999, 89.99999))->validate(-89));
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerJustBelowThreshold(): void
    {
        $this->assertTrue((new LatitudeRange(-89.99999, 89.99999))->validate(89));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerAboveThreshold(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate(91));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerBelowThreshold(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate(-91));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerMatchingLowerBound(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate(-90));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerMatchingHigherBound(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate(90));
    }

    public function testValidateReturnsTrueWhenPassingAFloatJustAboveThreshold(): void
    {
        $this->assertTrue((new LatitudeRange(-89.99999, 89.99999))->validate(-89.9999));
    }

    public function testValidateReturnsTrueWhenPassingAFloatJustBelowThreshold(): void
    {
        $this->assertTrue((new LatitudeRange(-89.99999, 89.99999))->validate(89.9999));
    }

    public function testValidateReturnsFalseWhenPassingAFloatAboveThreshold(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate(90.0001));
    }

    public function testValidateReturnsFalseWhenPassingAFloatBelowThreshold(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate(-90.0001));
    }

    public function testValidateReturnsFalseWhenPassingAFloatMatchingLowerBound(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate(-90.0000));
    }

    public function testValidateReturnsFalseWhenPassingAFloatMatchingHigherBound(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate(90.0000));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsTrueWhenPassingAStringIntegerJustAboveThreshold(): void
    {
        $this->assertTrue((new LatitudeRange(-89.99999, 89.99999))->validate('-89'));
    }

    public function testValidateReturnsTrueWhenPassingAStringIntegerJustBelowThreshold(): void
    {
        $this->assertTrue((new LatitudeRange(-89.99999, 89.99999))->validate('89'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerAboveThreshold(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate('91'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerBelowThreshold(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate('-91'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerMatchingLowerBound(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate('-90'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerMatchingHigherBound(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate('90'));
    }

    public function testValidateReturnsTrueWhenPassingAStringFloatJustAboveThreshold(): void
    {
        $this->assertTrue((new LatitudeRange(-89.99999, 89.99999))->validate('-89.9999'));
    }

    public function testValidateReturnsTrueWhenPassingAStringFloatJustBelowThreshold(): void
    {
        $this->assertTrue((new LatitudeRange(-89.99999, 89.99999))->validate('89.9999'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatAboveThreshold(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate('90.0001'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatBelowThreshold(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate('-90.0001'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatMatchingLowerBound(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate('-90.0000'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatMatchingHigherBound(): void
    {
        $this->assertFalse((new LatitudeRange(-89.99999, 89.99999))->validate('90.0000'));
    }

    public function testValidateReturnsTrueWhenPassingAStringIntegerJustAboveMinimumValue(): void
    {
        $this->assertTrue((new LatitudeRange(60, 80))->validate('61'));
    }

    public function testValidateReturnsTrueWhenPassingAStringIntegerJustBelowMaximumValue(): void
    {
        $this->assertTrue((new LatitudeRange(60, 80))->validate('79'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerAboveMaximumValue(): void
    {
        $this->assertFalse((new LatitudeRange(60, 80))->validate('81'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerBelowMinimumValue(): void
    {
        $this->assertFalse((new LatitudeRange(60, 80))->validate('59'));
    }

    public function testValidateReturnsTrueWhenPassingAStringIntegerMatchingMinimumValue(): void
    {
        $this->assertTrue((new LatitudeRange(60, 80))->validate('60'));
    }

    public function testValidateReturnsTrueWhenPassingAStringIntegerMatchingMaximumValue(): void
    {
        $this->assertTrue((new LatitudeRange(60, 80))->validate('80'));
    }

    public function testValidateReturnsTrueWhenPassingAStringFloatJustAboveMinimumValue(): void
    {
        $this->assertTrue((new LatitudeRange(60.555, 80.334))->validate('60.556'));
    }

    public function testValidateReturnsTrueWhenPassingAStringFloatJustBelowMaximumValue(): void
    {
        $this->assertTrue((new LatitudeRange(60.555, 80.334))->validate('80.333'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatBelowMinimumValue(): void
    {
        $this->assertFalse((new LatitudeRange(60.555, 80.334))->validate('60.554'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatAboveMaximumValue(): void
    {
        $this->assertFalse((new LatitudeRange(60.555, 80.334))->validate('80.335'));
    }

    public function testValidateReturnsTrueWhenPassingAStringFloatMatchingMinimumValue(): void
    {
        $this->assertTrue((new LatitudeRange(60.555, 80.334))->validate('60.555'));
    }

    public function testValidateReturnsTrueWhenPassingAStringFloatMatchingMaximumValue(): void
    {
        $this->assertTrue((new LatitudeRange(60.555, 80.334))->validate('80.334'));
    }
}
