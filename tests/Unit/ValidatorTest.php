<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit;

use Amp\Promise;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Text\MinimumLength;
use HarmonyIO\Validation\Validator;

class ValidatorTest extends TestCase
{
    public function testValidateReturnsPromise(): void
    {
        $this->assertInstanceOf(
            Promise::class,
            (new Validator(new MinimumLength(3)))->validate('Test value')
        );
    }

    public function testValidateReturnsTrueOnValidValidation(): void
    {
        $this->assertTrue((new Validator(new MinimumLength(3)))->validate('Test value'));
    }

    public function testValidateReturnsFalseOnInvalidValidation(): void
    {
        $this->assertFalse((new Validator(new MinimumLength(20)))->validate('Test value'));
    }
}
