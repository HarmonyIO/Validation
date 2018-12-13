<?php declare(strict_types=1);

namespace HarmonyIO\Validation;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Result\Promise\Failure;
use HarmonyIO\Validation\Result\Promise\Success;

function fail(string $message, ?Parameter $parameter = null): Promise
{
    if ($parameter === null) {
        return new Failure(new Error($message));
    }

    return new Failure(new Error($message, $parameter));
}

function failWithError(Error $error, Error ...$errors): Promise
{
    array_unshift($errors, $error);

    return new Failure(...$errors);
}

function succeed(): Promise
{
    return new Success();
}
