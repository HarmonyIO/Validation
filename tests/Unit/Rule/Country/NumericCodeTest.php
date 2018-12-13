<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Country;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Country\NumericCode;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class NumericCodeTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, NumericCode::class);
    }

    public function testValidateFailsWhenAnInvalidAlpha2CountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new NumericCode())->validate('529'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Country.NumericCode', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAValidAlpha2CountryCode(): void
    {
        /** @var Result $result */
        $result = wait((new NumericCode())->validate('528'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
