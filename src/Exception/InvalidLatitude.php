<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Exception;

final class InvalidLatitude extends Exception
{
    private const MESSAGE = 'Provided latitude (`%s`) must be within range -90 to 90 (exclusive).';

    public function __construct(float $latitude)
    {
        parent::__construct(sprintf(self::MESSAGE, $latitude));
    }
}
