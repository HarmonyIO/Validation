<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\CreditCard;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\CreditCard\Discover;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class DiscoverTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Discover::class);
    }

    /**
     * @dataProvider provideInvalidCreditCardNumbers
     */
    public function testValidateFailsOnInvalidCreditCardNumber(string $creditCardNumber): void
    {
        /** @var Result $result */
        $result = wait((new Discover())->validate($creditCardNumber));

        $this->assertFalse($result->isValid());
        $this->assertSame('CreditCard.Discover', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideInvalidCreditCardCheckSums
     */
    public function testValidateFailsOnInvalidCreditCardCheckSums(string $creditCardNumber): void
    {
        /** @var Result $result */
        $result = wait((new Discover())->validate($creditCardNumber));

        $this->assertFalse($result->isValid());
        $this->assertSame('CreditCard.LuhnChecksum', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideValidCreditCardNumbers
     */
    public function testValidateSucceedsOnValidCreditCardNumber(string $creditCardNumber): void
    {
        /** @var Result $result */
        $result = wait((new Discover())->validate($creditCardNumber));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    /**
     * @return string[]
     */
    public function provideInvalidCreditCardNumbers(): array
    {
        return [
            ['601195026665572'],
            ['6711672088793450'],
            ['6511241089786739'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideInvalidCreditCardCheckSums(): array
    {
        return [
            ['6011950266655720'],
            ['6011004755012033'],
            ['6011672088793451'],
            ['6011241089786730'],
        ];
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
}
