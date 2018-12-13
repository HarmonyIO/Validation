<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Network\IpAddress;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Network\IpAddress\NotInReservedRange;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class NotInReservedRangeTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, NotInReservedRange::class);
    }

    /**
     * @dataProvider provideInvalidIpv4Addresses
     */
    public function testValidateFailsWhenPassingAnIpv4AddressThatIsWithinTheReservedRange(string $ipAddress): void
    {
        /** @var Result $result */
        $result = wait((new NotInReservedRange())->validate($ipAddress));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.IpAddress.NotInReservedRange', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideInvalidIpv6Addresses
     */
    public function testValidateFailsWhenPassingAnIpv6AddressThatIsWithinTheReservedRange(string $ipAddress): void
    {
        /** @var Result $result */
        $result = wait((new NotInReservedRange())->validate($ipAddress));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.IpAddress.NotInReservedRange', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideValidIpv4Addresses
     */
    public function testValidateSucceedsWhenPassingAnIpv4AddressThatIsNotWithinTheReservedRange(string $ipAddress): void
    {
        /** @var Result $result */
        $result = wait((new NotInReservedRange())->validate($ipAddress));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    /**
     * @dataProvider provideValidIpv6Addresses
     */
    public function testValidateSucceedsWhenPassingAnIpv6AddressThatIsNotWithinTheReservedRange(string $ipAddress): void
    {
        /** @var Result $result */
        $result = wait((new NotInReservedRange())->validate($ipAddress));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    /**
     * @return string[]
     */
    public function provideInvalidIpv4Addresses(): array
    {
        return [
            ['0.0.0.0'],
            ['0.255.255.255'],
            ['100.64.0.0'],
            ['100.127.255.255'],
            ['127.0.0.0'],
            ['127.255.255.255'],
            ['169.254.0.0'],
            ['169.254.255.255'],
            ['198.51.100.0'],
            ['198.51.100.255'],
            ['203.0.113.0'],
            ['203.0.113.255'],
            ['224.0.0.0'],
            ['239.255.255.255'],
            ['240.0.0.0'],
            ['255.255.255.254'],
            ['255.255.255.255'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideInvalidIpv6Addresses(): array
    {
        return [
            ['::'],
            ['::1'],
            ['100::'],
            ['100::ffff:ffff:ffff:ffff'],
            ['2001:db8::'],
            ['2001:db8:ffff:ffff:ffff:ffff:ffff:ffff'],
            ['fc00::'],
            ['fdff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'],
            ['fe80::'],
            ['febf:ffff:ffff:ffff:ffff:ffff:ffff:ffff'],
            ['ff00::'],
            ['ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideValidIpv4Addresses(): array
    {
        return [
            ['1.1.1.1'],
            ['100.63.255.255'],
            ['100.128.0.0'],
            ['126.255.255.255'],
            ['128.0.0.0'],
            ['169.253.255.255'],
            ['169.255.0.0'],
            ['198.51.99.255'],
            ['198.51.101.0'],
            ['203.0.112.255'],
            ['203.0.114.0'],
            ['223.255.255.255'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideValidIpv6Addresses(): array
    {
        return [
            ['::2'],
            ['99::ffff:ffff:ffff:ffff'],
            ['101::'],
            ['2001:db7:ffff:ffff:ffff:ffff:ffff:ffff'],
            ['2001:db9::'],
            ['fbff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'],
            ['fe00::'],
            ['fe79:ffff:ffff:ffff:ffff:ffff:ffff:ffff'],
            ['fecf::'],
        ];
    }
}
