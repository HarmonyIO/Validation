<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\GeoLocation;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\GeoLocation\Latitude;
use HarmonyIO\Validation\Rule\Rule;

class LatitudeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Latitude());
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerJustAboveThreshold(): void
    {
        $this->assertTrue((new Latitude())->validate(-89));
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerJustBelowThreshold(): void
    {
        $this->assertTrue((new Latitude())->validate(89));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerAboveThreshold(): void
    {
        $this->assertFalse((new Latitude())->validate(91));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerBelowThreshold(): void
    {
        $this->assertFalse((new Latitude())->validate(-91));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerMatchingLowerBound(): void
    {
        $this->assertFalse((new Latitude())->validate(-90));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerMatchingHigherBound(): void
    {
        $this->assertFalse((new Latitude())->validate(90));
    }

    public function testValidateReturnsTrueWhenPassingAFloatJustAboveThreshold(): void
    {
        $this->assertTrue((new Latitude())->validate(-89.9999));
    }

    public function testValidateReturnsTrueWhenPassingAFloatJustBelowThreshold(): void
    {
        $this->assertTrue((new Latitude())->validate(89.9999));
    }

    public function testValidateReturnsFalseWhenPassingAFloatAboveThreshold(): void
    {
        $this->assertFalse((new Latitude())->validate(90.0001));
    }

    public function testValidateReturnsFalseWhenPassingAFloatBelowThreshold(): void
    {
        $this->assertFalse((new Latitude())->validate(-90.0001));
    }

    public function testValidateReturnsFalseWhenPassingAFloatMatchingLowerBound(): void
    {
        $this->assertFalse((new Latitude())->validate(-90.0000));
    }

    public function testValidateReturnsFalseWhenPassingAFloatMatchingHigherBound(): void
    {
        $this->assertFalse((new Latitude())->validate(90.0000));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Latitude())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Latitude())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Latitude())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Latitude())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Latitude())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Latitude())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsTrueWhenPassingAStringIntegerJustAboveThreshold(): void
    {
        $this->assertTrue((new Latitude())->validate('-89'));
    }

    public function testValidateReturnsTrueWhenPassingAStringIntegerJustBelowThreshold(): void
    {
        $this->assertTrue((new Latitude())->validate('89'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerAboveThreshold(): void
    {
        $this->assertFalse((new Latitude())->validate('91'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerBelowThreshold(): void
    {
        $this->assertFalse((new Latitude())->validate('-91'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerMatchingLowerBound(): void
    {
        $this->assertFalse((new Latitude())->validate('-90'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerMatchingHigherBound(): void
    {
        $this->assertFalse((new Latitude())->validate('90'));
    }

    public function testValidateReturnsTrueWhenPassingAStringFloatJustAboveThreshold(): void
    {
        $this->assertTrue((new Latitude())->validate('-89.9999'));
    }

    public function testValidateReturnsTrueWhenPassingAStringFloatJustBelowThreshold(): void
    {
        $this->assertTrue((new Latitude())->validate('89.9999'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatAboveThreshold(): void
    {
        $this->assertFalse((new Latitude())->validate('90.0001'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatBelowThreshold(): void
    {
        $this->assertFalse((new Latitude())->validate('-90.0001'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatMatchingLowerBound(): void
    {
        $this->assertFalse((new Latitude())->validate('-90.0000'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatMatchingHigherBound(): void
    {
        $this->assertFalse((new Latitude())->validate('90.0000'));
    }
}
