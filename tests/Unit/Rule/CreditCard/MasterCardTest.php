<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\CreditCard;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\CreditCard\MasterCard;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class MasterCardTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, MasterCard::class);
    }

    /**
     * @dataProvider provideInvalidCreditCardNumbers
     */
    public function testValidateFailsOnInvalidCreditCardNumber(string $creditCardNumber): void
    {
        /** @var Result $result */
        $result = wait((new MasterCard())->validate($creditCardNumber));

        $this->assertFalse($result->isValid());
        $this->assertSame('CreditCard.MasterCard', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideInvalidCreditCardCheckSums
     */
    public function testValidateFailsOnInvalidCreditCardCheckSums(string $creditCardNumber): void
    {
        /** @var Result $result */
        $result = wait((new MasterCard())->validate($creditCardNumber));

        $this->assertFalse($result->isValid());
        $this->assertSame('CreditCard.LuhnChecksum', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideValidCreditCardNumbers
     */
    public function testValidateSucceedsOnValidCreditCardNumber(string $creditCardNumber): void
    {
        /** @var Result $result */
        $result = wait((new MasterCard())->validate($creditCardNumber));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
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
        ];
    }

    /**
     * @return string[]
     */
    public function provideInvalidCreditCardCheckSums(): array
    {
        return [
            ['5405873638276924'],
            ['5511793680071836'],
            ['5185497765654616'],
            ['5317787159451483'],
            ['5244891802525954'],
        ];
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
}
