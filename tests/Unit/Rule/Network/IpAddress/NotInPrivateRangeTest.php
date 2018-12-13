<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Network\IpAddress;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Network\IpAddress\NotInPrivateRange;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class NotInPrivateRangeTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, NotInPrivateRange::class);
    }

    /**
     * @dataProvider provideInvalidIpv4Addresses
     */
    public function testValidateFailsWhenPassingAnIpv4AddressThatIsWithinThePrivateRange(string $ipAddress): void
    {
        /** @var Result $result */
        $result = wait((new NotInPrivateRange())->validate($ipAddress));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.IpAddress.NotInPrivateRange', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideInvalidIpv6Addresses
     */
    public function testValidateFailsWhenPassingAnIpv6AddressThatIsWithinThePrivateRange(string $ipAddress): void
    {
        /** @var Result $result */
        $result = wait((new NotInPrivateRange())->validate($ipAddress));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.IpAddress.NotInPrivateRange', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideValidIpv4Addresses
     */
    public function testValidateSucceedsWhenPassingAnIpv4AddressThatIsNotWithinThePrivateRange(string $ipAddress): void
    {
        /** @var Result $result */
        $result = wait((new NotInPrivateRange())->validate($ipAddress));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    /**
     * @dataProvider provideValidIpv6Addresses
     */
    public function testValidateSucceedsWhenPassingAnIpv6AddressThatIsNotWithinThePrivateRange(string $ipAddress): void
    {
        /** @var Result $result */
        $result = wait((new NotInPrivateRange())->validate($ipAddress));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
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
    public function provideInvalidIpv6Addresses(): array
    {
        return [
            ['fd12:3456:789a:1::1'],
        ];
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
    public function provideValidIpv6Addresses(): array
    {
        return [
            ['2001:0db8:85a3:0000:0000:8a2e:0370:7334'],
            ['2001:cdba:0000:0000:0000:0000:3257:9652'],
            ['FE80::0202:B3FF:FE1E:8329'],
        ];
    }
}
