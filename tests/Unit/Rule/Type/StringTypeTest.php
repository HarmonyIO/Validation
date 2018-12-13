<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Type;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Type\StringType;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class StringTypeTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, StringType::class);
    }

    public function testValidateSucceedsWhenPassingAString(): void
    {
        /** @var Result $result */
        $result = wait((new StringType())->validate('€'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
