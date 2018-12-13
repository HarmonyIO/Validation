<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Network\IpAddress;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Network\IpAddress\Ipv4;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class Ipv4Test extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Ipv4::class);
    }

    public function testValidateFailsWhenPassingAnIpv6Address(): void
    {
        /** @var Result $result */
        $result = wait((new Ipv4())->validate('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.IpAddress.Ipv4', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnInvalidIpv4Address(): void
    {
        /** @var Result $result */
        $result = wait((new Ipv4())->validate('x.1.2.3'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.IpAddress.Ipv4', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAValidIpv4Address(): void
    {
        /** @var Result $result */
        $result = wait((new Ipv4())->validate('192.168.1.1'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
