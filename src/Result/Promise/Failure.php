<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Result\Promise;

use Amp\Loop;
use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;

final class Failure implements Promise
{
    /** @var Error[] */
    private $errors = [];

    public function __construct(Error $error, Error ...$errors)
    {
        $this->errors[] = $error;

        foreach ($errors as $error) {
            $this->errors[] = $error;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onResolve(callable $onResolved)
    {
        try {
            $onResolved(null, new Result(false, ...$this->errors));
        } catch (\Throwable $exception) {
            Loop::defer(static function () use ($exception): void {
                throw $exception;
            });
        }
    }
}
