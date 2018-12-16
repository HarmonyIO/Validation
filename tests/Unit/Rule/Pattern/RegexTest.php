<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Pattern;

use HarmonyIO\Validation\Exception\InvalidRegexPattern;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Pattern\Regex;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class RegexTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Regex::class, '~foo~');
    }

    public function testConstructorThrowsOnInvalidPatternWithMissingEndingDelimiter(): void
    {
        $this->expectException(InvalidRegexPattern::class);
        $this->expectExceptionMessage('Provided regex pattern (`~foo`) is not valid.');

        new Regex('~foo');
    }

    public function testValidateFailsWhenPassingANonMatchingString(): void
    {
        /** @var Result $result */
        $result = wait((new Regex('~foo~'))->validate('bar'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Pattern.Regex', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAMatchingString(): void
    {
        /** @var Result $result */
        $result = wait((new Regex('~foo~'))->validate('foo'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
