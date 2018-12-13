<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\GeoLocation;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\GeoLocation\Longitude;
use HarmonyIO\ValidationTest\Unit\Rule\NumericTestCase;
use function Amp\Promise\wait;

class LongitudeTest extends NumericTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Longitude::class);
    }

    public function testValidateFailsWhenPassingAnIntegerBelowThreshold(): void
    {
        /** @var Result $result */
        $result = wait((new Longitude())->validate(-180));

        $this->assertFalse($result->isValid());
        $this->assertSame('GeoLocation.Longitude', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnIntegerAboveThreshold(): void
    {
        /** @var Result $result */
        $result = wait((new Longitude())->validate(180));

        $this->assertFalse($result->isValid());
        $this->assertSame('GeoLocation.Longitude', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnIntegerAsAStringBelowThreshold(): void
    {
        /** @var Result $result */
        $result = wait((new Longitude())->validate('-180'));

        $this->assertFalse($result->isValid());
        $this->assertSame('GeoLocation.Longitude', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnIntegerAsAStringAboveThreshold(): void
    {
        /** @var Result $result */
        $result = wait((new Longitude())->validate('180'));

        $this->assertFalse($result->isValid());
        $this->assertSame('GeoLocation.Longitude', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAFloatBelowThreshold(): void
    {
        /** @var Result $result */
        $result = wait((new Longitude())->validate(-180.0));

        $this->assertFalse($result->isValid());
        $this->assertSame('GeoLocation.Longitude', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAFloatAboveThreshold(): void
    {
        /** @var Result $result */
        $result = wait((new Longitude())->validate(180.0));

        $this->assertFalse($result->isValid());
        $this->assertSame('GeoLocation.Longitude', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAFloatAsAStringBelowThreshold(): void
    {
        /** @var Result $result */
        $result = wait((new Longitude())->validate('-180.0'));

        $this->assertFalse($result->isValid());
        $this->assertSame('GeoLocation.Longitude', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAFloatAsAStringAboveThreshold(): void
    {
        /** @var Result $result */
        $result = wait((new Longitude())->validate('180.0'));

        $this->assertFalse($result->isValid());
        $this->assertSame('GeoLocation.Longitude', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAnIntegerWithInLowerRange(): void
    {
        /** @var Result $result */
        $result = wait((new Longitude())->validate(-179));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnIntegerWithInHigherRange(): void
    {
        /** @var Result $result */
        $result = wait((new Longitude())->validate(179));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnIntegerAsAStringWithInLowerRange(): void
    {
        /** @var Result $result */
        $result = wait((new Longitude())->validate('-179'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnIntegerAsAStringWithInHigherRange(): void
    {
        /** @var Result $result */
        $result = wait((new Longitude())->validate('179'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAFloatWithInLowerRange(): void
    {
        /** @var Result $result */
        $result = wait((new Longitude())->validate(-179.9));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAFloatWithInHigherRange(): void
    {
        /** @var Result $result */
        $result = wait((new Longitude())->validate(179.9));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAFloatAsAStringWithInLowerRange(): void
    {
        /** @var Result $result */
        $result = wait((new Longitude())->validate('-179.9'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAFloatAsAStringWithInHigherRange(): void
    {
        /** @var Result $result */
        $result = wait((new Longitude())->validate('179.9'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
