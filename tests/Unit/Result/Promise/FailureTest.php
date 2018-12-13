<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Result\Promise;

use Amp\Loop;
use Amp\Promise;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Promise\Failure;
use HarmonyIO\Validation\Result\Result;

class FailureTest extends TestCase
{
    public function testObjectImplementsPromise(): void
    {
        $this->assertInstanceOf(Promise::class, new Failure(new Error('Some.Error')));
    }

    public function testPromiseResolvesToResultObject(): void
    {
        (new Failure(new Error('Some.Error')))->onResolve(function ($error, Result $result): void {
            $this->assertNull($error);
            $this->assertInstanceOf(Result::class, $result);
            $this->assertFalse($result->isValid());
            $this->assertCount(1, $result->getErrors());
        });
    }

    public function testPromiseResolvesToResultObjectWithMultipleErrors(): void
    {
        (new Failure(new Error('Some.Error1'), new Error('Some.Error2')))->onResolve(function ($error, Result $result): void {
            $this->assertNull($error);
            $this->assertInstanceOf(Result::class, $result);
            $this->assertFalse($result->isValid());
            $this->assertCount(2, $result->getErrors());
            $this->assertSame('Some.Error1', $result->getErrors()[0]->getMessage());
            $this->assertSame('Some.Error2', $result->getErrors()[1]->getMessage());
        });
    }

    public function testPromiseThrowsException(): void
    {
        Loop::run(function (): void {
            // phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('Something went wrong!');

            (new Failure(new Error('Some.Error')))->onResolve(function ($error, Result $result): void {
                $this->assertNull($error);
                $this->assertInstanceOf(Result::class, $result);
                $this->assertFalse($result->isValid());
                $this->assertCount(1, $result->getErrors());

                throw new \Exception('Something went wrong!');
            });
        });
    }
}
