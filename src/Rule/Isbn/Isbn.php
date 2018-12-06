<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Isbn;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Combinator\Any;
use HarmonyIO\Validation\Rule\Rule;

class Isbn implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        return (new Any(
            new Isbn10(),
            new Isbn13()
        ))->validate($value);
    }
}
