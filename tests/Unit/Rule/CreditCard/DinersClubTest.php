<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\CreditCard;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\CreditCard\DinersClub;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class DinersClubTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, DinersClub::class);
    }

    /**
     * @dataProvider provideInvalidCreditCardNumbers
     */
    public function testValidateFailsOnInvalidCreditCardNumber(string $creditCardNumber): void
    {
        /** @var Result $result */
        $result = wait((new DinersClub())->validate($creditCardNumber));

        $this->assertFalse($result->isValid());
        $this->assertSame('CreditCard.DinersClub', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideInvalidCreditCardCheckSums
     */
    public function testValidateFailsOnInvalidCreditCardCheckSums(string $creditCardNumber): void
    {
        /** @var Result $result */
        $result = wait((new DinersClub())->validate($creditCardNumber));

        $this->assertFalse($result->isValid());
        $this->assertSame('CreditCard.LuhnChecksum', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideValidCreditCardNumbers
     */
    public function testValidateSucceedsOnValidCreditCardNumber(string $creditCardNumber): void
    {
        /** @var Result $result */
        $result = wait((new DinersClub())->validate($creditCardNumber));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
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

    /**
     * @return string[]
     */
    public function provideInvalidCreditCardCheckSums(): array
    {
        return [
            ['36959113591685'],
            ['38900251000764'],
            ['30036290155570'],
            ['36755763114194'],
            ['38915532819170'],
        ];
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
}
