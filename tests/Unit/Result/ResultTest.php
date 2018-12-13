<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Result;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;

class ResultTest extends TestCase
{
    public function testIsValidReturnsTrueWhenValid(): void
    {
        $this->assertTrue((new Result(true))->isValid());
    }

    public function testIsValidReturnsFalseWhenNotValid(): void
    {
        $this->assertFalse((new Result(false))->isValid());
    }

    public function testGetFirstErrorReturnsNullWhenNoErrorsAreAvailable(): void
    {
        $this->assertNull((new Result(true))->getFirstError());
    }

    public function testGetFirstErrorReturnsErrorWhenOneErrorIsAvailable(): void
    {
        $error = new Error('Test.Error');

        $this->assertSame($error, (new Result(true, $error))->getFirstError());
    }

    public function testGetFirstErrorReturnsFirstErrorWhenMultipleErrorsAreAvailable(): void
    {
        $error = new Error('Test.Error1');

        $this->assertSame($error, (new Result(true, $error, new Error('Test.Error2')))->getFirstError());
    }

    public function testGetErrorsWhenNoErrorsAreAvailable(): void
    {
        $this->assertCount(0, (new Result(true))->getErrors());
    }

    public function testGetErrorsWhenOneErrorIsAvailable(): void
    {
        $result = new Result(true, new Error('Test.Error'));

        $this->assertCount(1, $result->getErrors());
        $this->assertSame('Test.Error', (new Result(true, new Error('Test.Error')))->getErrors()[0]->getMessage());
    }

    public function testGetErrorsWhenMultipleErrorsAreAvailable(): void
    {
        $result = new Result(true, new Error('Test.Error1'), new Error('Test.Error2'));

        $this->assertCount(2, $result->getErrors());
        $this->assertSame('Test.Error1', $result->getErrors()[0]->getMessage());
        $this->assertSame('Test.Error2', $result->getErrors()[1]->getMessage());
    }
}
