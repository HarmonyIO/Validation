<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\CreditCard;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\CreditCard\LuhnChecksum;
use HarmonyIO\Validation\Rule\Rule;

class LuhnChecksumTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new LuhnChecksum());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new LuhnChecksum())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new LuhnChecksum())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new LuhnChecksum())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new LuhnChecksum())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new LuhnChecksum())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new LuhnChecksum())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new LuhnChecksum())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new LuhnChecksum())->validate(static function (): void {
        }));
    }

    /**
     * @dataProvider provideValidCreditCardNumbers
     */
    public function testValidateReturnsTrueWhenPassingAValidCreditCardNumber(string $creditCardNumber): void
    {
        $this->assertTrue((new LuhnChecksum())->validate($creditCardNumber));
    }

    /**
     * @dataProvider provideInvalidCreditCardNumbers
     */
    public function testValidateReturnsFalseWhenPassingAnInvalidCreditCardNumber(string $creditCardNumber): void
    {
        $this->assertFalse((new LuhnChecksum())->validate($creditCardNumber));
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
            ['4024007132208751'],
            ['4024007125242569'],
            ['4485880955865523'],
            ['4532443321333982'],
            ['4532586245120149'],
            ['4024007127821790'],
            ['4916035543952502'],
            ['4381501965850645'],
            ['4929415214329922'],
            ['4539188477666775'],
            ['4337286741311'],
            ['4483736350483'],
            ['4716908666040'],
            ['4532893390839'],
            ['4532130927864'],
            ['4929173825536'],
            ['4716110276091'],
            ['4916217802860'],
            ['4716461604065'],
            ['4716995392898'],
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
            ['40036290155579'],
            ['30636290155579'],
            ['37755763114193'],
            ['601195026665572'],
            ['60110047550120322'],
            ['6711672088793450'],
            ['6511241089786739'],
            ['6011706849798969'],
            ['540587363827692'],
            ['55117936800718355'],
            ['5638013614388422'],
            ['5185497765654616'],
            ['4485880955865524'],
            ['433728674131'],
            ['44837363504833'],
            ['4716908666041'],
        ];
    }
}
