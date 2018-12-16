<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Exception;

final class InvalidRegexPattern extends Exception
{
    private const MESSAGE = 'Provided regex pattern (`%s`) is not valid.';

    public function __construct(string $pattern)
    {
        parent::__construct(sprintf(self::MESSAGE, $pattern));
    }
}
