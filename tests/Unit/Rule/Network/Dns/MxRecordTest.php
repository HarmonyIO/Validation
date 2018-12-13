<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Network\Dns;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Network\Dns\MxRecord;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class MxRecordTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, MxRecord::class);
    }

    public function testValidateFailsWhenPassingADomainWithoutAnMxRecord(): void
    {
        /** @var Result $result */
        $result = wait((new MxRecord())->validate('example.com'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.Dns.MxRecord', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingADomainWithAnMxRecord(): void
    {
        /** @var Result $result */
        $result = wait((new MxRecord())->validate('gmail.com'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
