<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\NationalId;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\NationalId\Bsn;
use HarmonyIO\Validation\Rule\Rule;

class BsnTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Bsn());
    }

    /**
     * @dataProvider provideValidBsnIntegers
     */
    public function testValidateReturnsTrueWhenPassingAValidInteger(int $bsn): void
    {
        $this->assertTrue((new Bsn())->validate($bsn));
    }

    /**
     * @dataProvider provideInvalidBsnIntegers
     */
    public function testValidateReturnsFalseWhenPassingAnInvalidInteger(int $bsn): void
    {
        $this->assertFalse((new Bsn())->validate($bsn));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Bsn())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Bsn())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Bsn())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Bsn())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Bsn())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Bsn())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Bsn())->validate(static function (): void {
        }));
    }

    /**
     * @dataProvider provideValidBsnStrings
     */
    public function testValidateReturnsTrueWhenPassingAValidBsnString(string $bsn): void
    {
        $this->assertTrue((new Bsn())->validate($bsn));
    }

    /**
     * @dataProvider provideInvalidBsnStrings
     */
    public function testValidateReturnsFalseWhenPassingAnInvalidBsnString(string $bsn): void
    {
        $this->assertFalse((new Bsn())->validate($bsn));
    }

    /**
     * @return string[]
     */
    public function provideValidBsnStrings(): array
    {
        return [
            ['393610603'],
            ['404176884'],
            ['209544077'],
            ['768131108'],
            ['320300869'],
            ['717256236'],
            ['733192592'],
            ['281495324'],
            ['952254694'],
            ['708454343'],
            ['380694414'],
            ['237374353'],
            ['937735851'],
            ['238141408'],
            ['387371710'],
            ['425445070'],
            ['722472559'],
            ['210502022'],
            ['319040483'],
            ['434877724'],
            ['910579210'],
            ['755815713'],
            ['278886243'],
            ['486636975'],
            ['139697949'],
            ['497084399'],
            ['765789851'],
            ['688916466'],
            ['675789357'],
            ['295543899'],
            ['220524166'],
            ['394617617'],
            ['545921478'],
            ['257320763'],
            ['442195035'],
            ['705305569'],
            ['628534012'],
            ['151121072'],
            ['465147513'],
            ['511566335'],
            ['452545900'],
            ['315390359'],
            ['687256872'],
            ['249682187'],
            ['904181005'],
            ['516221681'],
            ['873749054'],
            ['580248604'],
            ['840861680'],
            ['775514056'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideInvalidBsnStrings(): array
    {
        return [
            ['150059922'],
            ['696509060'],
            ['520186190'],
            ['382069889'],
            ['777307702'],
            ['647760300'],
            ['778787561'],
            ['136844008'],
            ['435156786'],
            ['297343367'],
            ['511889647'],
            ['787564982'],
            ['813058446'],
            ['360082446'],
            ['925403073'],
            ['713172269'],
            ['374128343'],
            ['292319109'],
            ['627679734'],
            ['876666297'],
            ['927000089'],
            ['518888015'],
            ['613561884'],
            ['432391695'],
            ['868780567'],
            ['417617462'],
            ['976355513'],
            ['628571249'],
            ['971886364'],
            ['489595786'],
            ['544300393'],
            ['988525673'],
            ['835253999'],
            ['284348237'],
            ['636851922'],
            ['732044062'],
            ['901810143'],
            ['840789560'],
            ['492742158'],
            ['417981074'],
            ['951780258'],
            ['903669022'],
            ['460023582'],
            ['655710189'],
            ['314460787'],
            ['634970085'],
            ['127498441'],
            ['440981444'],
            ['940893004'],
            ['764699110'],
        ];
    }

    /**
     * @return int[]
     */
    public function provideValidBsnIntegers(): array
    {
        return [
            [393610603],
            [404176884],
            [209544077],
            [768131108],
            [320300869],
            [717256236],
            [733192592],
            [281495324],
            [952254694],
            [708454343],
            [380694414],
            [237374353],
            [937735851],
            [238141408],
            [387371710],
            [425445070],
            [722472559],
            [210502022],
            [319040483],
            [434877724],
            [910579210],
            [755815713],
            [278886243],
            [486636975],
            [139697949],
            [497084399],
            [765789851],
            [688916466],
            [675789357],
            [295543899],
            [220524166],
            [394617617],
            [545921478],
            [257320763],
            [442195035],
            [705305569],
            [628534012],
            [151121072],
            [465147513],
            [511566335],
            [452545900],
            [315390359],
            [687256872],
            [249682187],
            [904181005],
            [516221681],
            [873749054],
            [580248604],
            [840861680],
            [775514056],
        ];
    }

    /**
     * @return int[]
     */
    public function provideInvalidBsnIntegers(): array
    {
        return [
            [150059922],
            [696509060],
            [520186190],
            [382069889],
            [777307702],
            [647760300],
            [778787561],
            [136844008],
            [435156786],
            [297343367],
            [511889647],
            [787564982],
            [813058446],
            [360082446],
            [925403073],
            [713172269],
            [374128343],
            [292319109],
            [627679734],
            [876666297],
            [927000089],
            [518888015],
            [613561884],
            [432391695],
            [868780567],
            [417617462],
            [976355513],
            [628571249],
            [971886364],
            [489595786],
            [544300393],
            [988525673],
            [835253999],
            [284348237],
            [636851922],
            [732044062],
            [901810143],
            [840789560],
            [492742158],
            [417981074],
            [951780258],
            [903669022],
            [460023582],
            [655710189],
            [314460787],
            [634970085],
            [127498441],
            [440981444],
            [940893004],
            [764699110],
        ];
    }
}
