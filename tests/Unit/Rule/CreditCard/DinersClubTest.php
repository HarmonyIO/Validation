<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\CreditCard;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\CreditCard\DinersClub;
use HarmonyIO\Validation\Rule\Rule;

class DinersClubTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new DinersClub());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new DinersClub())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new DinersClub())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new DinersClub())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new DinersClub())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new DinersClub())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new DinersClub())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new DinersClub())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new DinersClub())->validate(static function (): void {
        }));
    }

    /**
     * @dataProvider provideValidCreditCardNumbers
     */
    public function testValidateReturnsTrueWhenPassingAValidCreditCardNumber(string $creditCardNumber): void
    {
        $this->assertTrue((new DinersClub())->validate($creditCardNumber));
    }

    /**
     * @dataProvider provideInvalidCreditCardNumbers
     */
    public function testValidateReturnsFalseWhenPassingAnInvalidCreditCardNumber(string $creditCardNumber): void
    {
        $this->assertFalse((new DinersClub())->validate($creditCardNumber));
    }

    /**
     * @return string[]
     */
    public function provideValidCreditCardNumbers(): array
    {
        return [
            ['36959113591684'],
            ['38900251000763'],
            ['30036290155579'],
            ['36755763114193'],
            ['38915532819179'],
            ['36663412645148'],
            ['30213597214520'],
            ['30028631487803'],
            ['30056498652045'],
            ['30071971913772'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideInvalidCreditCardNumbers(): array
    {
        return [
            ['3695911359168'],
            ['389002510007633'],
            ['40036290155579'],
            ['30636290155579'],
            ['37755763114193'],
        ];
    }
}
