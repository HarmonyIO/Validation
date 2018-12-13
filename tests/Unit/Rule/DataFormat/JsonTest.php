<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\DataFormat;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\DataFormat\Json;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class JsonTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Json::class);
    }

    public function testValidateFailsWhenPassingAnInvalidJsonString(): void
    {
        /** @var Result $result */
        $result = wait((new Json())->validate('{foo: "bar"}'));

        $this->assertFalse($result->isValid());
        $this->assertSame('DataFormat.Json', $result->getFirstError()->getMessage());
    }

    public function testValidateReturnsTrueWhenPassingAValidJsonString(): void
    {
        /** @var Result $result */
        $result = wait((new Json())->validate('{"foo": "bar"}'));

        $this->assertTrue($result->isValid());
        $this->assertCount(0, $result->getErrors());
    }
}
