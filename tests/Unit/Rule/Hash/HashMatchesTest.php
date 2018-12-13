<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Hash;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Hash\HashMatches;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class HashMatchesTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, HashMatches::class, '1234567890');
    }

    public function testValidateFailsWhenHashIsInvalid(): void
    {
        /** @var Result $result */
        $result = wait((new HashMatches('1234567890'))->validate('123456789'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Hash.HashMatches', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenHashIsValid(): void
    {
        /** @var Result $result */
        $result = wait((new HashMatches('1234567890'))->validate('1234567890'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
