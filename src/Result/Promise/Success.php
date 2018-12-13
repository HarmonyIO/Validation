<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Result\Promise;

use Amp\Loop;
use Amp\Promise;
use HarmonyIO\Validation\Result\Result;

final class Success implements Promise
{
    /**
     * {@inheritdoc}
     */
    public function onResolve(callable $onResolved)
    {
        try {
            $onResolved(null, new Result(true));
        } catch (\Throwable $exception) {
            Loop::defer(static function () use ($exception): void {
                throw $exception;
            });
        }
    }
}
