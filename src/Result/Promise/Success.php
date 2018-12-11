<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Result\Promise;

use Amp\Coroutine;
use Amp\Loop;
use Amp\Promise;
use HarmonyIO\Validation\Result\Result;
use React\Promise\PromiseInterface as ReactPromise;

final class Success implements Promise
{
    /**
     * {@inheritdoc}
     */
    public function onResolve(callable $onResolved)
    {
        try {
            $result = $onResolved(null, new Result(true));

            if ($result === null) {
                return;
            }

            if ($result instanceof \Generator) {
                $result = new Coroutine($result);
            }

            if ($result instanceof Promise || $result instanceof ReactPromise) {
                Promise\rethrow($result);
            }
        } catch (\Throwable $exception) {
            Loop::defer(static function () use ($exception): void {
                throw $exception;
            });
        }
    }
}
