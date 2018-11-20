<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Iban;

class IbanTest extends TestCase
{
    /** @var Iban */
    private $iban;

    // phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->iban = new class() extends Iban
        {
            public function test(string $ibanCode): bool
            {
                return $this->validateChecksum($ibanCode);
            }
        };
    }

    /**
     * @dataProvider provideInvalidIbanCodes
     */
    public function testValidateChecksumReturnsFalseOnInvalidChecksum(string $ibanCode): void
    {
        $this->assertFalse($this->iban->test($ibanCode));
    }

    /**
     * @dataProvider provideValidIbanCodes
     */
    public function testValidateChecksumReturnsTrueOnValidChecksum(string $ibanCode): void
    {
        $this->assertTrue($this->iban->test($ibanCode));
    }

    /**
     * @return string[]
     */
    public function provideInvalidIbanCodes(): array
    {
        return [
            ['AL901490122010010999234354354'],
            ['AD1200012030200359100101'],
            ['AT611904300234573202'],
            ['AZ21NABZ00000000137010001945'],
            ['BH67BMAG000012991234567'],
            ['BE685390075470345'],
            ['BA3912900794010284945'],
            ['BR9700360305000010009795493P12'],
            ['BG80BNBG966110203456789'],
            ['CR050152020010262840667'],
            ['HR12100100518630001601'],
            ['CY170020012800000012005276001'],
            ['CZ65080000001920001453990'],
            ['DK50004004401162434'],
            ['DO28BAGR000000012124536113245'],
            ['TL3800800123456789101578'],
            ['EE3822002210201456856'],
            ['FO20004004401162434'],
            ['FI21123456000007856'],
            ['FR1420041010050500013M026067'],
            ['GE29NB00000001019049178'],
            ['DE893704004405320130001'],
            ['GI75NWBK0000000070994534'],
            ['GR16011012500000000123006956'],
            ['GL20004004401162434'],
            ['GT82TRAJ010200000012100296901'],
            ['HU421177301611111018000000001'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideValidIbanCodes(): array
    {
        return [
            ['AL47212110090000000235698741'],
            ['AD1200012030200359100100'],
            ['AT611904300234573201'],
            ['AZ21NABZ00000000137010001944'],
            ['BH67BMAG00001299123456'],
            ['BE68539007547034'],
            ['BA391290079401028494'],
            ['BR9700360305000010009795493P1'],
            ['BG80BNBG96611020345678'],
            ['CR05015202001026284066'],
            ['HR1210010051863000160'],
            ['CY17002001280000001200527600'],
            ['CZ6508000000192000145399'],
            ['DK5000400440116243'],
            ['DO28BAGR00000001212453611324'],
            ['TL380080012345678910157'],
            ['EE382200221020145685'],
            ['FO2000400440116243'],
            ['FI2112345600000785'],
            ['FR1420041010050500013M02606'],
            ['GE29NB0000000101904917'],
            ['DE89370400440532013000'],
            ['GI75NWBK000000007099453'],
            ['GR1601101250000000012300695'],
            ['GL2000400440116243'],
            ['GT82TRAJ01020000001210029690'],
            ['HU42117730161111101800000000'],
        ];
    }
}
