<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Network\IpAddress;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Network\IpAddress\Ipv4;
use HarmonyIO\Validation\Rule\Rule;

class Ipv4Test extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Ipv4());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Ipv4())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Ipv4())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Ipv4())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Ipv4())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Ipv4())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Ipv4())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Ipv4())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Ipv4())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsTrueWhenPassingAValidIpv4Address(): void
    {
        $this->assertTrue((new Ipv4())->validate('192.168.1.1'));
    }

    public function testValidateReturnsFalseWhenPassingAnIpv6Address(): void
    {
        $this->assertFalse((new Ipv4())->validate('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));
    }
}
