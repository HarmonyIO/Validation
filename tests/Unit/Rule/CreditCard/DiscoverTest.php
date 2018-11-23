<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\CreditCard;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\CreditCard\Discover;
use HarmonyIO\Validation\Rule\Rule;

class DiscoverTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Discover());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Discover())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Discover())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Discover())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Discover())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Discover())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Discover())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Discover())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Discover())->validate(static function (): void {
        }));
    }

    /**
     * @dataProvider provideValidCreditCardNumbers
     */
    public function testValidateReturnsTrueWhenPassingAValidCreditCardNumber(string $creditCardNumber): void
    {
        $this->assertTrue((new Discover())->validate($creditCardNumber));
    }

    /**
     * @dataProvider provideInvalidCreditCardNumbers
     */
    public function testValidateReturnsFalseWhenPassingAnInvalidCreditCardNumber(string $creditCardNumber): void
    {
        $this->assertFalse((new Discover())->validate($creditCardNumber));
    }

    /**
     * @return string[]
     */
    public function provideValidCreditCardNumbers(): array
    {
        return [
            ['6011950266655729'],
            ['6011004755012032'],
            ['6011672088793450'],
            ['6011241089786739'],
            ['6011706849798968'],
            ['6011118026537856'],
            ['6011442367332051'],
            ['6011363004302549'],
            ['6011367098870360'],
            ['6011286241931285'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideInvalidCreditCardNumbers(): array
    {
        return [
            ['601195026665572'],
            ['60110047550120322'],
            ['6711672088793450'],
            ['6511241089786739'],
            ['6011706849798969'],
        ];
    }
}
