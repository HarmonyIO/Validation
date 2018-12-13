<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\GeoLocation;

use HarmonyIO\Validation\Exception\InvalidLongitude;
use HarmonyIO\Validation\Exception\InvalidNumericalRange;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\GeoLocation\LongitudeRange;
use HarmonyIO\ValidationTest\Unit\Rule\NumericTestCase;
use function Amp\Promise\wait;

class LongitudeRangeTest extends NumericTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, LongitudeRange::class, 60, 80);
    }
    
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

    public function testValidateFailsWhenPassingAnIntegerBelowThreshold(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(60, 80))->validate(-180));

        $this->assertFalse($result->isValid());
        $this->assertSame('GeoLocation.Longitude', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnIntegerAboveThreshold(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(60, 80))->validate(180));

        $this->assertFalse($result->isValid());
        $this->assertSame('GeoLocation.Longitude', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnIntegerAsAStringBelowThreshold(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(60, 80))->validate('-180'));

        $this->assertFalse($result->isValid());
        $this->assertSame('GeoLocation.Longitude', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnIntegerAsAStringAboveThreshold(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(60, 80))->validate('180'));

        $this->assertFalse($result->isValid());
        $this->assertSame('GeoLocation.Longitude', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAFloatBelowThreshold(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(60, 80))->validate(-180.0));

        $this->assertFalse($result->isValid());
        $this->assertSame('GeoLocation.Longitude', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAFloatAboveThreshold(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(60, 80))->validate(180.0));

        $this->assertFalse($result->isValid());
        $this->assertSame('GeoLocation.Longitude', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAFloatAsAStringBelowThreshold(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(60, 80))->validate('-180.0'));

        $this->assertFalse($result->isValid());
        $this->assertSame('GeoLocation.Longitude', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAFloatAsAStringAboveThreshold(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(60, 80))->validate('180.0'));

        $this->assertFalse($result->isValid());
        $this->assertSame('GeoLocation.Longitude', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnIntegerSmallerThanMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(13, 16))->validate(12));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Minimum', $result->getFirstError()->getMessage());
        $this->assertSame('minimum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(13, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAnIntegerBiggerThanMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(13, 16))->validate(17));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Maximum', $result->getFirstError()->getMessage());
        $this->assertSame('maximum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(16, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAnIntegerAsAStringSmallerThanMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(13, 16))->validate('12'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Minimum', $result->getFirstError()->getMessage());
        $this->assertSame('minimum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(13, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAnIntegerAsAStringBiggerThanMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(13, 16))->validate('17'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Maximum', $result->getFirstError()->getMessage());
        $this->assertSame('maximum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(16, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAFloatSmallerThanMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(13, 16))->validate(12.9));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Minimum', $result->getFirstError()->getMessage());
        $this->assertSame('minimum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(13, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAFloatBiggerThanMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(13, 16))->validate(16.1));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Maximum', $result->getFirstError()->getMessage());
        $this->assertSame('maximum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(16, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAFloatAsAStringSmallerThanMinimum(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(13, 16))->validate('12.9'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Minimum', $result->getFirstError()->getMessage());
        $this->assertSame('minimum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(13, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAFloatAsAStringBiggerThanMaximum(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(13, 16))->validate('16.1'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Maximum', $result->getFirstError()->getMessage());
        $this->assertSame('maximum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(16, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateSucceedsWhenPassingAnIntegerWithinRange(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(13, 16))->validate(13));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnIntegerAsStringWithinRange(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(13, 16))->validate('13'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAFloatWithinRange(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(13, 16))->validate(13.0));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAFloatAsStringWithinRange(): void
    {
        /** @var Result $result */
        $result = wait((new LongitudeRange(13, 16))->validate('13.0'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
