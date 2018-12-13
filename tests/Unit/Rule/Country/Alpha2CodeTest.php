<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Country\Alpha2Code;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class Alpha2CodeTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Alpha2Code::class);
    }

    public function testValidateFailsOnAnInvalidAlpha2CountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Alpha2Code())->validate('XX'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Country.Alpha2Code', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsOnAValidLowercaseAlpha2CountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Alpha2Code())->validate('nl'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsOnAValidUppercaseAlpha2CountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new Alpha2Code())->validate('NL'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
