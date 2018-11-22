<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\CreditCard;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\CreditCard\AmericanExpress;
use HarmonyIO\Validation\Rule\Rule;

class AmericanExpressTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new AmericanExpress());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new AmericanExpress())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new AmericanExpress())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new AmericanExpress())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new AmericanExpress())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new AmericanExpress())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new AmericanExpress())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new AmericanExpress())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new AmericanExpress())->validate(static function (): void {
        }));
    }

    /**
     * @dataProvider provideValidCreditCardNumbers
     */
    public function testValidateReturnsTrueWhenPassingAValidCreditCardNumber(string $creditCardNumber): void
    {
        $this->assertTrue((new AmericanExpress())->validate($creditCardNumber));
    }

    /**
     * @dataProvider provideInvalidCreditCardNumbers
     */
    public function testValidateReturnsFalseWhenPassingAnInvalidCreditCardNumber(string $creditCardNumber): void
    {
        $this->assertFalse((new AmericanExpress())->validate($creditCardNumber));
    }

    /**
     * @return string[]
     */
    public function provideValidCreditCardNumbers(): array
    {
        return [
            ['343279748844889'],
            ['341103612979716'],
            ['370670303243077'],
            ['344630746638914'],
            ['373866131351507'],
            ['373248240904304'],
            ['370046389763843'],
            ['342919263077362'],
            ['375491374480752'],
            ['346266142717495'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideInvalidCreditCardNumbers(): array
    {
        return [
            ['34327974884488'],
            ['3411036129797166'],
            ['470670303243077'],
            ['354630746638914'],
        ];
    }
}
