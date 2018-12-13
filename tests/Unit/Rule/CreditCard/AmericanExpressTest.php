<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\CreditCard;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\CreditCard\AmericanExpress;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class AmericanExpressTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, AmericanExpress::class);
    }

    /**
     * @dataProvider provideInvalidCreditCardNumbers
     */
    public function testValidateFailsOnInvalidCreditCardNumber(string $creditCardNumber): void
    {
        /** @var Result $result */
        $result = wait((new AmericanExpress())->validate($creditCardNumber));

        $this->assertFalse($result->isValid());
        $this->assertSame('CreditCard.AmericanExpress', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideInvalidCreditCardCheckSums
     */
    public function testValidateFailsOnInvalidCreditCardCheckSums(string $creditCardNumber): void
    {
        /** @var Result $result */
        $result = wait((new AmericanExpress())->validate($creditCardNumber));

        $this->assertFalse($result->isValid());
        $this->assertSame('CreditCard.LuhnChecksum', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideValidCreditCardNumbers
     */
    public function testValidateSucceedsOnValidCreditCardNumber(string $creditCardNumber): void
    {
        /** @var Result $result */
        $result = wait((new AmericanExpress())->validate($creditCardNumber));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
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

    /**
     * @return string[]
     */
    public function provideInvalidCreditCardCheckSums(): array
    {
        return [
            ['343279748844880'],
            ['341103612979717'],
            ['370670303243078'],
            ['344630746638915'],
        ];
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
}
