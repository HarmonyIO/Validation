<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Email;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Email\RfcEmailAddress;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class RfcEmailAddressTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, RfcEmailAddress::class);
    }

    public function testValidateFailsWhenEmailAddressIsInvalid(): void
    {
        /** @var Result $result */
        $result = wait((new RfcEmailAddress())->validate('invalid-email-address'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Email.RfcEmailAddress', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenEmailAddressIsValid(): void
    {
        /** @var Result $result */
        $result = wait((new RfcEmailAddress())->validate('test@example.com'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
