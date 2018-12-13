<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Network\IpAddress;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Network\IpAddress\Ipv6;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class Ipv6Test extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Ipv6::class);
    }

    public function testValidateFailsWhenPassingAnIpv4Address(): void
    {
        /** @var Result $result */
        $result = wait((new Ipv6())->validate('192.168.1.1'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.IpAddress.Ipv6', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnInvalidIpv6Address(): void
    {
        /** @var Result $result */
        $result = wait((new Ipv6())->validate('x001:0db8:85a3:0000:0000:8a2e:0370:7334'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.IpAddress.Ipv6', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAValidIpv6Address(): void
    {
        /** @var Result $result */
        $result = wait((new Ipv6())->validate('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
