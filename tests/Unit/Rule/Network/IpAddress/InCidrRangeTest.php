<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Network\IpAddress;

use HarmonyIO\Validation\Exception\InvalidCidrRange;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Network\IpAddress\InCidrRange;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class InCidrRangeTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, InCidrRange::class);
    }

    public function testConstructorThrowsOnGarbageCidrRange(): void
    {
        $this->markTestSkipped('Can we capture warnings in the `InCidrRange` class?');

        $this->expectException(InvalidCidrRange::class);

        new InCidrRange('x.0.0.0/12');
    }

    public function testConstructorThrowsOnInvalidIpv4CidrRange(): void
    {
        $this->expectException(InvalidCidrRange::class);

        new InCidrRange('0.0.0.0/33');
    }

    public function testConstructorThrowsOnInvalidIpv6CidrRange(): void
    {
        $this->expectException(InvalidCidrRange::class);

        new InCidrRange('2620:0:861:1::/129');
    }

    public function testValidateFailsWhenPassingAnIpv4AddressBelowRange(): void
    {
        /** @var Result $result */
        $result = wait((new InCidrRange('10.0.0.0/24'))->validate('9.255.255.255'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.IpAddress.InCidrRange', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnIpv4AddressAboveRange(): void
    {
        /** @var Result $result */
        $result = wait((new InCidrRange('10.0.0.0/24'))->validate('10.0.1.0'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.IpAddress.InCidrRange', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnIpv4AddressBelowRanges(): void
    {
        /** @var Result $result */
        $result = wait((new InCidrRange('9.0.0.0/24', '10.0.0.0/24'))->validate('8.255.255.255'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.IpAddress.InCidrRange', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnIpv4AddressAboveRanges(): void
    {
        /** @var Result $result */
        $result = wait((new InCidrRange('9.0.0.0/24', '10.0.0.0/24'))->validate('10.0.1.0'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.IpAddress.InCidrRange', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnIpv6AddressBelowRange(): void
    {
        /** @var Result $result */
        $result = wait((new InCidrRange('2620:0:2d0:200::7/24'))->validate('2619:ff:ffff:ffff:ffff:ffff:ffff:ffff'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.IpAddress.InCidrRange', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnIpv6AddressAboveRange(): void
    {
        /** @var Result $result */
        $result = wait((new InCidrRange('2620:0:2d0:200::7/24'))->validate('2621:0:0:0:0:0:0:0'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.IpAddress.InCidrRange', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnIpv6AddressBelowRanges(): void
    {
        /** @var Result $result */
        // phpcs:ignore PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket
        $result = wait((new InCidrRange('2619:0:2d0:200::7/24', '2620:0:2d0:200::7/24'))
            ->validate('2618:ff:ffff:ffff:ffff:ffff:ffff:ffff')
        );

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.IpAddress.InCidrRange', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAnIpv6AddressAboveRanges(): void
    {
        /** @var Result $result */
        // phpcs:ignore PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket
        $result = wait((new InCidrRange('2619:0:2d0:200::7/24', '2620:0:2d0:200::7/24'))
            ->validate('2621:0:0:0:0:0:0:0')
        );

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.IpAddress.InCidrRange', $result->getFirstError()->getMessage());
    }
    
    public function testValidateSucceedsTrueWhenPassingAnIpv4AddressWithinRange(): void
    {
        /** @var Result $result */
        $result = wait((new InCidrRange('10.0.0.0/24'))->validate('10.0.0.1'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateReturnsTrueWhenPassingAnIpv4AddressWithinTheRangeOfTheSecondDefinedRange(): void
    {
        /** @var Result $result */
        $result = wait((new InCidrRange('9.0.0.0/24', '10.0.0.0/24'))->validate('10.0.0.1'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsTrueWhenPassingAnIpv6AddressWithinRange(): void
    {
        /** @var Result $result */
        $result = wait((new InCidrRange('2620:0:2d0:200::7/24'))->validate('2620:0:0:0:0:0:0:0'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateReturnsTrueWhenPassingAnIpv6AddressWithinTheRangeOfTheSecondDefinedRange(): void
    {
        /** @var Result $result */
        // phpcs:ignore PSR2.Methods.FunctionCallSignature.SpaceBeforeCloseBracket
        $result = wait((new InCidrRange('2619:0:2d0:200::7/24', '2620:0:2d0:200::7/24'))
            ->validate('2620:0:0:0:0:0:0:0')
        );

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
