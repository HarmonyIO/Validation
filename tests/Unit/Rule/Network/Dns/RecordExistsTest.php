<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Network\Dns;

use HarmonyIO\Validation\Enum\Network\Dns\RecordType;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Network\Dns\RecordExists;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class RecordExistsTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, RecordExists::class, RecordType::MX());
    }

    public function testValidateFailsWhenPassingADomainWithoutAnMxRecord(): void
    {
        /** @var Result $result */
        $result = wait((new RecordExists(RecordType::MX()))->validate('example.com'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.Dns.RecordExists', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingADomainWithAnMxRecord(): void
    {
        /** @var Result $result */
        $result = wait((new RecordExists(RecordType::MX()))->validate('gmail.com'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
