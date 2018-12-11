<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Checksum;
use HarmonyIO\Validation\Rule\Rule;

class IbanChecksumTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Checksum());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Checksum())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Checksum())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Checksum())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Checksum())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Checksum())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Checksum())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Checksum())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Checksum())->validate(static function (): void {
        }));
    }

    /**
     * @dataProvider provideInvalidIbanCodes
     */
    public function testValidateChecksumReturnsFalseOnInvalidChecksum(string $ibanCode): void
    {
        $this->assertFalse((new Checksum())->validate($ibanCode));
    }

    /**
     * @dataProvider provideValidIbanCodes
     */
    public function testValidateChecksumReturnsTrueOnValidChecksum(string $ibanCode): void
    {
        $this->assertTrue((new Checksum())->validate($ibanCode));
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
            ['IS1401592600765455107303390'],
            ['IE29AIBK931152123456789'],
            ['IL6201080000000999999990'],
            ['IT60X05428111010000001234567'],
            ['JO94CBJO00100000000001310003023'],
            ['KZ86125KZT50041001001'],
            ['XK0512120123456789067'],
            ['KW81CBKU00000000000012345601012'],
            ['LV80BANK00004351950012'],
            ['LB620999000000010019012291145'],
            ['LI21088100002324013AAB'],
            ['LT1210000111010010001'],
            ['LU2800194006447500001'],
            ['MK072501200000589845'],
            ['MT84MALT011000012345MTLCAST001ST'],
            ['MR13000200010100001234567534'],
            ['MU17BOMM0101101030300200000MURS'],
            ['MC58112220000101234567890301'],
            ['MD24AG0002251000131041689'],
            ['ME255050000123456789512'],
            ['NL91ABNA04171643001'],
            ['NO93860111179478'],
            ['PK36SCBL00000011234567023'],
            ['PS92PALS0000000004001234567023'],
            ['PL611090101400000712198128745'],
            ['PT500002012312345678901545'],
            ['QA58DOHB00001234567890ABCDEFGH'],
            ['RO49AAAA1B310075938400001'],
            ['SM86U03225098000000002701001'],
            ['SA03800000006080101675190'],
            ['RS352600056010016113790'],
            ['SK31120000001987426375412'],
            ['SI561910000001234389'],
            ['ES91210004184502000513324'],
            ['SE45500000000583982574667'],
            ['CH93007620116238529578'],
            ['TN59100060351835984788312'],
            ['TR3300061005197864578413267'],
            ['AE0703312345678901234567'],
            ['GB29NWBK601613319268190'],
            ['VG96VPVG00000123456789012'],
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
            ['IS140159260076545510730339'],
            ['IE29AIBK93115212345678'],
            ['IL620108000000099999999'],
            ['IT60X0542811101000000123456'],
            ['JO94CBJO0010000000000131000302'],
            ['KZ86125KZT5004100100'],
            ['XK051212012345678906'],
            ['KW81CBKU0000000000001234560101'],
            ['LV80BANK0000435195001'],
            ['LB62099900000001001901229114'],
            ['LI21088100002324013AA'],
            ['LT121000011101001000'],
            ['LU280019400644750000'],
            ['MK07250120000058984'],
            ['MT84MALT011000012345MTLCAST001S'],
            ['MR1300020001010000123456753'],
            ['MU17BOMM0101101030300200000MUR'],
            ['MC5811222000010123456789030'],
            ['MD24AG000225100013104168'],
            ['ME25505000012345678951'],
            ['NL91ABNA0417164300'],
            ['NO9386011117947'],
            ['PK36SCBL0000001123456702'],
            ['PS92PALS000000000400123456702'],
            ['PL61109010140000071219812874'],
            ['PT50000201231234567890154'],
            ['QA58DOHB00001234567890ABCDEFG'],
            ['RO49AAAA1B31007593840000'],
            ['SM86U0322509800000000270100'],
            ['SA0380000000608010167519'],
            ['RS35260005601001611379'],
            ['SK3112000000198742637541'],
            ['SI56191000000123438'],
            ['ES9121000418450200051332'],
            ['SE4550000000058398257466'],
            ['CH9300762011623852957'],
            ['TN5910006035183598478831'],
            ['TR330006100519786457841326'],
            ['AE070331234567890123456'],
            ['GB29NWBK60161331926819'],
            ['VG96VPVG0000012345678901'],
        ];
    }
}
