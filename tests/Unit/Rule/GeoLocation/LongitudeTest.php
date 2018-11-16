<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\GeoLocation;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\GeoLocation\Longitude;
use HarmonyIO\Validation\Rule\Rule;

class LongitudeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Longitude());
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerJustAboveThreshold(): void
    {
        $this->assertTrue((new Longitude())->validate(-179));
    }

    public function testValidateReturnsTrueWhenPassingAnIntegerJustBelowThreshold(): void
    {
        $this->assertTrue((new Longitude())->validate(179));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerAboveThreshold(): void
    {
        $this->assertFalse((new Longitude())->validate(181));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerBelowThreshold(): void
    {
        $this->assertFalse((new Longitude())->validate(-181));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerMatchingLowerBound(): void
    {
        $this->assertFalse((new Longitude())->validate(-180));
    }

    public function testValidateReturnsFalseWhenPassingAnIntegerMatchingHigherBound(): void
    {
        $this->assertFalse((new Longitude())->validate(180));
    }

    public function testValidateReturnsTrueWhenPassingAFloatJustAboveThreshold(): void
    {
        $this->assertTrue((new Longitude())->validate(-179.9999));
    }

    public function testValidateReturnsTrueWhenPassingAFloatJustBelowThreshold(): void
    {
        $this->assertTrue((new Longitude())->validate(179.9999));
    }

    public function testValidateReturnsFalseWhenPassingAFloatAboveThreshold(): void
    {
        $this->assertFalse((new Longitude())->validate(180.0001));
    }

    public function testValidateReturnsFalseWhenPassingAFloatBelowThreshold(): void
    {
        $this->assertFalse((new Longitude())->validate(-180.0001));
    }

    public function testValidateReturnsFalseWhenPassingAFloatMatchingLowerBound(): void
    {
        $this->assertFalse((new Longitude())->validate(-180.0000));
    }

    public function testValidateReturnsFalseWhenPassingAFloatMatchingHigherBound(): void
    {
        $this->assertFalse((new Longitude())->validate(180.0000));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Longitude())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Longitude())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Longitude())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Longitude())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Longitude())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Longitude())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsTrueWhenPassingAStringIntegerJustAboveThreshold(): void
    {
        $this->assertTrue((new Longitude())->validate('-179'));
    }

    public function testValidateReturnsTrueWhenPassingAStringIntegerJustBelowThreshold(): void
    {
        $this->assertTrue((new Longitude())->validate('179'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerAboveThreshold(): void
    {
        $this->assertFalse((new Longitude())->validate('181'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerBelowThreshold(): void
    {
        $this->assertFalse((new Longitude())->validate('-181'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerMatchingLowerBound(): void
    {
        $this->assertFalse((new Longitude())->validate('-180'));
    }

    public function testValidateReturnsFalseWhenPassingAStringIntegerMatchingHigherBound(): void
    {
        $this->assertFalse((new Longitude())->validate('180'));
    }

    public function testValidateReturnsTrueWhenPassingAStringFloatJustAboveThreshold(): void
    {
        $this->assertTrue((new Longitude())->validate('-179.9999'));
    }

    public function testValidateReturnsTrueWhenPassingAStringFloatJustBelowThreshold(): void
    {
        $this->assertTrue((new Longitude())->validate('179.9999'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatAboveThreshold(): void
    {
        $this->assertFalse((new Longitude())->validate('180.0001'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatBelowThreshold(): void
    {
        $this->assertFalse((new Longitude())->validate('-180.0001'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatMatchingLowerBound(): void
    {
        $this->assertFalse((new Longitude())->validate('-180.0000'));
    }

    public function testValidateReturnsFalseWhenPassingAStringFloatMatchingHigherBound(): void
    {
        $this->assertFalse((new Longitude())->validate('180.0000'));
    }
}
