<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban\Country;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Germany;
use HarmonyIO\Validation\Rule\Rule;

class GermanyTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Germany());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Germany())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Germany())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Germany())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Germany())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Germany())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Germany())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Germany())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Germany())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenStringDoesNotStartWithCountryCode(): void
    {
        $this->assertFalse((new Germany())->validate('XE89370400440532013000'));
    }

    public function testValidateReturnsFalseWhenStringDoesNotHaveChecksum(): void
    {
        $this->assertFalse((new Germany())->validate('DEx9370400440532013000'));
    }

    public function testValidateReturnsFalseWhenStringDoesNotHaveBankAndBranchCode(): void
    {
        $this->assertFalse((new Germany())->validate('DE89x70400440532013000'));
    }

    public function testValidateReturnsFalseWhenStringDoesNotHaveAccountNumber(): void
    {
        $this->assertFalse((new Germany())->validate('DE8937040044053201300x'));
    }

    public function testValidateReturnsFalseWhenStringIsTooShort(): void
    {
        $this->assertFalse((new Germany())->validate('DE8937040044053201300'));
    }

    public function testValidateReturnsFalseWhenStringIsTooLong(): void
    {
        $this->assertFalse((new Germany())->validate('DE893704004405320130000'));
    }

    public function testValidateReturnsTrueWhenPassingAValidIbanString(): void
    {
        $this->assertTrue((new Germany())->validate('DE89370400440532013000'));
    }
}
