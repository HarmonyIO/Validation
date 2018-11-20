<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Gibraltar;
use HarmonyIO\Validation\Rule\Rule;

class GibraltarTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Gibraltar());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Gibraltar())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Gibraltar())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Gibraltar())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Gibraltar())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Gibraltar())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Gibraltar())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Gibraltar())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Gibraltar())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenStringDoesNotStartWithCountryCode(): void
    {
        $this->assertFalse((new Gibraltar())->validate('XI75NWBK000000007099453'));
    }

    public function testValidateReturnsFalseWhenStringDoesNotHaveChecksum(): void
    {
        $this->assertFalse((new Gibraltar())->validate('GIx5NWBK000000007099453'));
    }

    public function testValidateReturnsFalseWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        $this->assertFalse((new Gibraltar())->validate('GI75nWBK000000007099453'));
    }

    public function testValidateReturnsFalseWhenStringDoesNotHaveAccountNumber(): void
    {
        $this->assertFalse((new Gibraltar())->validate('GI75NWBK00000000709945!'));
    }

    public function testValidateReturnsFalseWhenStringIsTooShort(): void
    {
        $this->assertFalse((new Gibraltar())->validate('GI75NWBK00000000709945'));
    }

    public function testValidateReturnsFalseWhenStringIsTooLong(): void
    {
        $this->assertFalse((new Gibraltar())->validate('GI75NWBK0000000070994533'));
    }

    public function testValidateReturnsTrueWhenPassingAValidIbanString(): void
    {
        $this->assertTrue((new Gibraltar())->validate('GI75NWBK000000007099453'));
    }
}
