<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Network\IpAddress;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Network\IpAddress\NotInPrivateRange;
use HarmonyIO\Validation\Rule\Rule;

class NotInPrivateRangeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new NotInPrivateRange());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new NotInPrivateRange())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new NotInPrivateRange())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new NotInPrivateRange())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new NotInPrivateRange())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new NotInPrivateRange())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new NotInPrivateRange())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new NotInPrivateRange())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new NotInPrivateRange())->validate(static function (): void {
        }));
    }

    /**
     * @dataProvider provideValidIpv4Addresses
     */
    public function testValidateReturnsTrueWhenPassingAnIpv4AddressThatIsNotWithinThePrivateRange(string $ipAddress): void
    {
        $this->assertTrue((new NotInPrivateRange())->validate($ipAddress));
    }

    /**
     * @dataProvider provideInvalidIpv4Addresses
     */
    public function testValidateReturnsFalseWhenPassingAnIpv4AddressThatIsWithinThePrivateRange(string $ipAddress): void
    {
        $this->assertFalse((new NotInPrivateRange())->validate($ipAddress));
    }

    /**
     * @dataProvider provideValidIpv6Addresses
     */
    public function testValidateReturnsTrueWhenPassingAnIpv6AddressThatIsNotWithinThePrivateRange(string $ipAddress): void
    {
        $this->assertTrue((new NotInPrivateRange())->validate($ipAddress));
    }

    /**
     * @dataProvider provideInvalidIpv6Addresses
     */
    public function testValidateReturnsFalseWhenPassingAnIpv6AddressThatIsWithinThePrivateRange(string $ipAddress): void
    {
        $this->assertFalse((new NotInPrivateRange())->validate($ipAddress));
    }

    /**
     * @return string[]
     */
    public function provideValidIpv4Addresses(): array
    {
        return [
            ['1.1.1.1'],
            ['8.8.8.8'],
            ['255.255.255.255'],
            ['9.255.255.255'],
            ['11.0.0.0'],
            ['172.15.255.255'],
            ['172.32.0.0'],
            ['192.167.255.255'],
            ['192.169.0.0'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideInvalidIpv4Addresses(): array
    {
        return [
            ['10.0.0.0'],
            ['10.10.0.0'],
            ['10.10.10.0'],
            ['10.10.10.10'],
            ['10.255.255.255'],
            ['172.16.0.0'],
            ['172.22.0.0'],
            ['172.22.22.0'],
            ['172.22.22.22'],
            ['172.31.255.255'],
            ['192.168.0.0'],
            ['192.168.168.0'],
            ['192.168.168.168'],
            ['192.168.255.255'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideValidIpv6Addresses(): array
    {
        return [
            ['2001:0db8:85a3:0000:0000:8a2e:0370:7334'],
            ['2001:cdba:0000:0000:0000:0000:3257:9652'],
            ['FE80::0202:B3FF:FE1E:8329'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideInvalidIpv6Addresses(): array
    {
        return [
            ['fd12:3456:789a:1::1'],
        ];
    }
}
