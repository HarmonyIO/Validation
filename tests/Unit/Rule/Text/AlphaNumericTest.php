<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Text;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Text\AlphaNumeric;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class AlphaNumericTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, AlphaNumeric::class);
    }

    public function testValidateFailsWhenPassingANonAlphaNumericalString(): void
    {
        /** @var Result $result */
        $result = wait((new AlphaNumeric())->validate(' sdakjhsakh3287632786378'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Text.AlphaNumeric', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAnAlphaNumericalString(): void
    {
        /** @var Result $result */
        $result = wait((new AlphaNumeric())->validate('sdakjhsakh3287632786378'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
