<?php declare(strict_types=1);

namespace HarmonyIO\Validation;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Promise\Failure;
use HarmonyIO\Validation\Result\Promise\Success;
use HarmonyIO\Validation\Result\Result;

function fail(Error $error, Error ...$errors): Promise
{
    array_unshift($errors, $error);

    return new Failure(...$errors);
}

function succeed(): Promise
{
    return new Success();
}

function bubbleUp(Result $result): Promise
{
    if ($result->isValid()) {
        return succeed();
    }

    return fail(...$result->getErrors());
}
