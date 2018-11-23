<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\CreditCard;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\CreditCard\MasterCard;
use HarmonyIO\Validation\Rule\Rule;

class MasterCardTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new MasterCard());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new MasterCard())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new MasterCard())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new MasterCard())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new MasterCard())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new MasterCard())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new MasterCard())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new MasterCard())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new MasterCard())->validate(static function (): void {
        }));
    }

    /**
     * @dataProvider provideValidCreditCardNumbers
     */
    public function testValidateReturnsTrueWhenPassingAValidCreditCardNumber(string $creditCardNumber): void
    {
        $this->assertTrue((new MasterCard())->validate($creditCardNumber));
    }

    /**
     * @dataProvider provideInvalidCreditCardNumbers
     */
    public function testValidateReturnsFalseWhenPassingAnInvalidCreditCardNumber(string $creditCardNumber): void
    {
        $this->assertFalse((new MasterCard())->validate($creditCardNumber));
    }

    /**
     * @return string[]
     */
    public function provideValidCreditCardNumbers(): array
    {
        return [
            ['5405873638276923'],
            ['5511793680071835'],
            ['5538013614388422'],
            ['5185497765654615'],
            ['5317787159451482'],
            ['5505887768965575'],
            ['5244891802525953'],
            ['5516328401914735'],
            ['5571989499167494'],
            ['5572696289787568'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideInvalidCreditCardNumbers(): array
    {
        return [
            ['540587363827692'],
            ['55117936800718355'],
            ['5638013614388422'],
            ['5185497765654616'],
        ];
    }
}
