<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Network\IpAddress;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Exception\InvalidCidrRange;
use HarmonyIO\Validation\Rule\Network\IpAddress\InCidrRange;
use HarmonyIO\Validation\Rule\Rule;

class InCidrRangeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new InCidrRange());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new InCidrRange())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new InCidrRange())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new InCidrRange())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new InCidrRange())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new InCidrRange())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new InCidrRange())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new InCidrRange())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new InCidrRange())->validate(static function (): void {
        }));
    }

    public function testConstructorThrowsOnGarbageCidrRange():  void
    {
        $this->expectException(InvalidCidrRange::class);

        new InCidrRange('0.0.0.0/33');
    }

    public function testConstructorThrowsOnInvalidIpv4CidrRange():  void
    {
        $this->expectException(InvalidCidrRange::class);

        new InCidrRange('0.0.0.0/33');
    }

    public function testConstructorThrowsOnInvalidIpv6CidrRange():  void
    {
        $this->expectException(InvalidCidrRange::class);

        new InCidrRange('2620:0:861:1::/129');
    }

    public function testValidateReturnsTrueWhenPassingAValidIpv4Address(): void
    {
        $this->assertTrue((new InCidrRange('10.0.0.0/24'))->validate('10.0.0.1'));
        $this->assertTrue((new InCidrRange('10.0.0.0/24'))->validate('10.0.0.255'));
    }

    public function testValidateReturnsTrueWhenPassingAValidIpv4AddressWhichMatchesTheSecondDefinedCidrRange(): void
    {
        $this->assertTrue((new InCidrRange('9.0.0.0/24', '10.0.0.0/24'))->validate('10.0.0.1'));
        $this->assertTrue((new InCidrRange('9.0.0.0/24', '10.0.0.0/24'))->validate('10.0.0.255'));
    }

    public function testValidateReturnsFalseWhenPassingAnInvalidIpv4Address(): void
    {
        $this->assertFalse((new InCidrRange('10.0.0.0/24'))->validate('9.255.255.255'));
        $this->assertFalse((new InCidrRange('10.0.0.0/24'))->validate('10.0.1.0'));
    }

    public function testValidateReturnsFalseWhenPassingAnInvalidIpv4AddressAndMultipleDefinedCidrRanges(): void
    {
        $this->assertFalse((new InCidrRange('9.0.0.0/24', '10.0.0.0/24'))->validate('8.255.255.255'));
        $this->assertFalse((new InCidrRange('9.0.0.0/24', '10.0.0.0/24'))->validate('10.0.1.0'));
    }

    public function testValidateReturnsTrueWhenPassingAValidIpv6Address(): void
    {
        $this->assertTrue((new InCidrRange('2620:0:2d0:200::7/24'))->validate('2620:0:0:0:0:0:0:0'));
        $this->assertTrue((new InCidrRange('2620:0:2d0:200::7/24'))->validate('2620:ff:ffff:ffff:ffff:ffff:ffff:ffff'));
    }

    public function testValidateReturnsTrueWhenPassingAValidIpv6AddressWhichMatchesTheSecondDefinedCidrRange(): void
    {
        $this->assertTrue((new InCidrRange('2619:0:2d0:200::7/24', '2620:0:2d0:200::7/24'))->validate('2620:0:0:0:0:0:0:0'));
        $this->assertTrue((new InCidrRange('2619:0:2d0:200::7/24', '2620:0:2d0:200::7/24'))->validate('2620:ff:ffff:ffff:ffff:ffff:ffff:ffff'));
    }

    public function testValidateReturnsFalseWhenPassingAnInvalidIpv6Address(): void
    {
        $this->assertFalse((new InCidrRange('2620:0:2d0:200::7/24'))->validate('2619:ff:ffff:ffff:ffff:ffff:ffff:ffff'));
        $this->assertFalse((new InCidrRange('2620:0:2d0:200::7/24'))->validate('2621:0:0:0:0:0:0:0'));
    }

    public function testValidateReturnsFalseWhenPassingAnInvalidIpv6AddressAndMultipleDefinedCidrRanges(): void
    {
        $this->assertFalse(
            (new InCidrRange('2619:0:2d0:200::7/24', '2620:0:2d0:200::7/24'))->validate('2618:ff:ffff:ffff:ffff:ffff:ffff:ffff')
        );

        $this->assertFalse(
            (new InCidrRange('2619:0:2d0:200::7/24', '2620:0:2d0:200::7/24'))->validate('2621:0:0:0:0:0:0:0')
        );
    }
}
