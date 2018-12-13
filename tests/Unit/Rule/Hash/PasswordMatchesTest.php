<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Hash;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Hash\PasswordMatches;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class PasswordMatchesTest extends StringTestCase
{
    private const TEST_HASH = '$2y$10$PcRLWTmmlKptOuNnAZfmneSKIL7sSZ.j2ELZuNSncVSzqoovWNVzC';

    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, PasswordMatches::class, self::TEST_HASH);
    }

    public function testValidateFailsWhenPasswordIsInvalid(): void
    {
        /** @var Result $result */
        $result = wait((new PasswordMatches(self::TEST_HASH))->validate('123456789'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Hash.PasswordMatches', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPasswordIsValid(): void
    {
        /** @var Result $result */
        $result = wait((new PasswordMatches(self::TEST_HASH))->validate('1234567890'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
