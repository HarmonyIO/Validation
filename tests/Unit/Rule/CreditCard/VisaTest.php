<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\CreditCard;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\CreditCard\Visa;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class VisaTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Visa::class);
    }

    /**
     * @dataProvider provideInvalidCreditCardNumbers
     */
    public function testValidateFailsOnInvalidCreditCardNumber(string $creditCardNumber): void
    {
        /** @var Result $result */
        $result = wait((new Visa())->validate($creditCardNumber));

        $this->assertFalse($result->isValid());
        $this->assertSame('CreditCard.Visa', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideInvalidCreditCardCheckSums
     */
    public function testValidateFailsOnInvalidCreditCardCheckSums(string $creditCardNumber): void
    {
        /** @var Result $result */
        $result = wait((new Visa())->validate($creditCardNumber));

        $this->assertFalse($result->isValid());
        $this->assertSame('CreditCard.LuhnChecksum', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideValidCreditCardNumbers
     */
    public function testValidateSucceedsOnValidCreditCardNumber(string $creditCardNumber): void
    {
        /** @var Result $result */
        $result = wait((new Visa())->validate($creditCardNumber));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    /**
     * @return string[]
     */
    public function provideInvalidCreditCardNumbers(): array
    {
        return [
            ['402400713220875'],
            ['40240071252425699'],
            ['433728674131'],
            ['44837363504833'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideInvalidCreditCardCheckSums(): array
    {
        return [
            ['4024007132208752'],
            ['4485880955865524'],
            ['4532443321333983'],
            ['4916035543952503'],
            ['4337286741312'],
            ['4483736350484'],
            ['4716908666041'],
            ['4532893390830'],
            ['4929173825537'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideValidCreditCardNumbers(): array
    {
        return [
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
}
