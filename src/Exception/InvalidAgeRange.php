<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Exception;

class InvalidAgeRange extends Exception
{
    private const MESSAGE_TEMPLATE = 'The minimum age (`%d`) can not be greater than the maximum age (`%d`).';

    public function __construct(int $minimumAge, int $maximumAge)
    {
        parent::__construct(sprintf(self::MESSAGE_TEMPLATE, $minimumAge, $maximumAge));
    }
}
