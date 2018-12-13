<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit;

use Amp\Promise;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Text\MinimumLength;
use HarmonyIO\Validation\Validator;
use function Amp\Promise\wait;

class ValidatorTest extends TestCase
{
    public function testValidateReturnsPromise(): void
    {
        $this->assertInstanceOf(
            Promise::class,
            (new Validator(new MinimumLength(3)))->validate('Test value')
        );
    }

    public function testValidateFailsOnInvalidValidation(): void
    {
        /** @var Result $result */
        $result = wait((new Validator(new MinimumLength(20)))->validate('Test value'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Text.MinimumLength', $result->getFirstError()->getMessage());
        $this->assertSame('length', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(20, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateSucceedsOnValidValidation(): void
    {
        /** @var Result $result */
        $result = wait((new Validator(new MinimumLength(3)))->validate('Test value'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
