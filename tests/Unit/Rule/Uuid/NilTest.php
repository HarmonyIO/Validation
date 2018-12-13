<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Uuid;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Uuid\Nil;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class NilTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Nil::class);
    }

    public function testValidateFailsWhenPassingAnInvalidNilUuidString(): void
    {
        /** @var Result $result */
        $result = wait((new Nil())->validate('00000000-0000-0000-0000-000000000001'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Uuid.Nil', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAValidNilUuidString(): void
    {
        /** @var Result $result */
        $result = wait((new Nil())->validate('00000000-0000-0000-0000-000000000000'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
