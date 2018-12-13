<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Result\Promise;

use Amp\Loop;
use Amp\Promise;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Promise\Success;
use HarmonyIO\Validation\Result\Result;

class SuccessTest extends TestCase
{
    public function testObjectImplementsPromise(): void
    {
        $this->assertInstanceOf(Promise::class, new Success());
    }

    public function testPromiseResolvesToResultObject(): void
    {
        (new Success())->onResolve(function ($error, Result $result): void {
            $this->assertNull($error);
            $this->assertInstanceOf(Result::class, $result);
            $this->assertTrue($result->isValid());
            $this->assertCount(0, $result->getErrors());
        });
    }

    public function testPromiseThrowsException(): void
    {
        Loop::run(function (): void {
            // phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('Something went wrong!');

            (new Success())->onResolve(function ($error, Result $result): void {
                $this->assertNull($error);
                $this->assertInstanceOf(Result::class, $result);
                $this->assertTrue($result->isValid());
                $this->assertCount(0, $result->getErrors());

                throw new \Exception('Something went wrong!');
            });
        });
    }
}
