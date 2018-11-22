<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Network\Dns;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Enum\Network\Dns\RecordType;
use HarmonyIO\Validation\Rule\Network\Dns\RecordExists;
use HarmonyIO\Validation\Rule\Rule;

class RecordExistsTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new RecordExists(RecordType::MX()));
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new RecordExists(RecordType::MX()))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new RecordExists(RecordType::MX()))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new RecordExists(RecordType::MX()))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new RecordExists(RecordType::MX()))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new RecordExists(RecordType::MX()))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new RecordExists(RecordType::MX()))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new RecordExists(RecordType::MX()))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new RecordExists(RecordType::MX()))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsTrueWhenPassingADomainWithAnMxRecord(): void
    {
        $this->assertTrue((new RecordExists(RecordType::MX()))->validate('gmail.com'));
    }

    public function testValidateReturnsFalseWhenPassingADomainWithoutAnMxRecord(): void
    {
        $this->assertFalse((new RecordExists(RecordType::MX()))->validate('example.com'));
    }
}
